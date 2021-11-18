<?php

declare(strict_types=1);

namespace App\Data\Mappers;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Table;

/**
 * @Table(
 *  columns={"created_at": @Column(type = "datetime"), "updated_at": @Column(type = "datetime")},
 * )
 */
class TimestampedMapper extends Mapper
{
    public function queueCreate($entity, Node $node, State $state): ContextCarrierInterface
    {
        $cmd = parent::queueCreate($entity, $node, $state);

        $state->register('created_at', new \DateTimeImmutable(), true);
        $cmd->register('created_at', new \DateTimeImmutable(), true);

        $state->register('updated_at', new \DateTimeImmutable(), true);
        $cmd->register('updated_at', new \DateTimeImmutable(), true);

        return $cmd;
    }

    public function queueUpdate($entity, Node $node, State $state): ContextCarrierInterface
    {
        /** @var Update $cmd */
        $cmd = parent::queueUpdate($entity, $node, $state);

        $state->register('updated_at', new \DateTimeImmutable(), true);
        $cmd->registerAppendix('updated_at', new \DateTimeImmutable());

        return $cmd;
    }
}
