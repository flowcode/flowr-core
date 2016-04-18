<?php

namespace Flower\CoreBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Board\Task;

/**
 * Description of TaskService
 *
 * @author Pedro Barri <pbarri@flowcode.com.ar>
 */
class TaskService implements ContainerAwareInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
        $this->em = $this->container->get("doctrine.orm.entity_manager");
    }

    public function update($task)
    {
        /* send notifications */
        /*
        $pusher = $this->container->get('lopi_pusher.pusher');
        $boardId = $task->getBoard()->getId();
        if ($boardId > 0) {
            $pusher->trigger('board-' . $boardId, 'position-update', 'update');
        }*/
        $this->em->flush();
    }


    public function massiveUpdate($board)
    {
        /* send notifications */
        $pusher = $this->container->get('lopi_pusher.pusher');
        $boardId = $board->getId();
        $pusher->trigger('board-' . $boardId, 'position-update', 'update');
    }




}

       