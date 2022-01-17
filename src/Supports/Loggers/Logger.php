<?php

declare(strict_types=1);

namespace App\Supports\Loggers;

use App\Data\TypeCasts\Uuid;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;

/**
 * Logger.
 */
final class Logger
{
    /**
     * @var string The log file path.
     */
    private string $path;

    /**
     * @var string The log file name.
     */
    private string $filename;

    /**
     * @var int The log level.
     */
    private int $level;

    /**
     * @var bool The flag indicates logger in testing environment or not, 
     * true if the logger is in testing environment, otherwise false.
     */
    private bool $test;

    /**
     * @var array The log handler.
     */
    private array $handler = [];

    /**
     * The constructor.
     *
     * @param array $settings The settings.
     */
    public function __construct(array $settings = [])
    {
        $this->path = (string)($settings['path'] ?? '');
        $this->filename = (string)($settings['filename'] ?? 'app.log');
        $this->level = (int)($settings['level'] ?? MonologLogger::INFO);
        $this->test = (bool)($settings['test_environment']) ?? false;
    }

    /**
     * Build the logger.
     *
     * @param string|null $name The logging channel.
     *
     * @return LoggerInterface The logger.
     */
    public function createLogger(?string $name = null): LoggerInterface
    {
        $logger = new MonologLogger($name ?: (string)Uuid::create());

        // Environment conditional
        if (!$this->test) {
            foreach ($this->handler as $handler) {
                $logger->pushHandler($handler);
            }
        } else {
            $logger->pushHandler(new \Monolog\Handler\NullHandler());
        }

        $this->handler = [];

        return $logger;
    }

    /**
     * Add a handler.
     *
     * @param HandlerInterface $handler The handler.
     *
     * @return self The logger factory.
     */
    public function addHandler(HandlerInterface $handler): self
    {
        $this->handler[] = $handler;

        return $this;
    }

    /**
     * Add rotating file logger handler.
     *
     * @param string|null $filename The filename (optional).
     * @param int|null $level The level (optional).
     *
     * @return self The logger factory.
     */
    public function addFileHandler(?string $filename = null, ?int $level = null): self
    {
        $filename = sprintf('%s/%s', $this->path, $filename ?? $this->filename);

        /** @phpstan-ignore-next-line */
        $rotatingFileHandler = new RotatingFileHandler($filename, 0, $level ?? $this->level, true, 0777);

        // The last "true" here tells monolog to remove empty []'s
        $rotatingFileHandler->setFormatter(new LineFormatter(null, null, false, true));

        $this->addHandler($rotatingFileHandler);

        return $this;
    }

    /**
     * Add a console logger.
     *
     * @param int|null $level The level (optional).
     *
     * @return self The logger factory.
     */
    public function addConsoleHandler(?int $level = null): self
    {
        /** @phpstan-ignore-next-line */
        $streamHandler = new StreamHandler('php://stdout', $level ?? $this->level);
        $streamHandler->setFormatter(new LineFormatter(null, null, false, true));

        $this->addHandler($streamHandler);

        return $this;
    }
}
