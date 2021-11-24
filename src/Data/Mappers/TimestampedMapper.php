<?php

declare(strict_types=1);

namespace App\Data\Mappers;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Command\ContextCarrierInterface;

/**
 * The time stamp mapper.
 */
class TimestampedMapper extends Mapper
{
    /**
     * The create entity queue.
     * 
     * @param mixed $entity The entity.
     * @param Node $node The node.
     * @param State $state The state.
     * 
     * @return ContextCarrierInterface The context carrier interface.
     */
    public function queueCreate($entity, Node $node, State $state): ContextCarrierInterface
    {
        $cmd = parent::queueCreate($entity, $node, $state);

        $state->register('created_at', new \DateTimeImmutable(), true);
        $cmd->register('created_at', new \DateTimeImmutable(), true);

        $state->register('updated_at', new \DateTimeImmutable(), true);
        $cmd->register('updated_at', new \DateTimeImmutable(), true);

        return $cmd;
    }

    /**
     * The update entity queue.
     * 
     * @param mixed $entity The entity.
     * @param Node $node The node.
     * @param State $state The state.
     * 
     * @return ContextCarrierInterface The context carrier interface.
     */
    public function queueUpdate($entity, Node $node, State $state): ContextCarrierInterface
    {
        /** @var Update $cmd */
        $cmd = parent::queueUpdate($entity, $node, $state);

        $state->register('updated_at', new \DateTimeImmutable(), true);
        $cmd->registerAppendix('updated_at', new \DateTimeImmutable());

        return $cmd;
    }
}
