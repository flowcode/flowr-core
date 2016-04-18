<?php

namespace Flower\CoreBundle\Service;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Board\Task;
/**
 * Description of KanbanOrderService
 *
 * @author Pedro Barri <pbarri@flowcode.com.ar>
 */
class KanbanOrderService implements ContainerAwareInterface
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

    public function kanbanOrderChanged($positions)
    {
    	$em = $this->em;
    	$taskRepo = $em->getRepository("FlowerModelBundle:Board\Task");
        $taskStatusRepo = $em->getRepository("FlowerModelBundle:Board\TaskStatus");
        $task = null;
        foreach ($positions as $status) {
            $currentStatus = $taskStatusRepo->find($status["id"]);
            foreach ($status["tasks"] as $item) {
                $task = $taskRepo->find($item["Id"]);
                $task->setPosition($item["Position"]);
                $task->setStatus($currentStatus);
                $em->flush();
            }
        }

        /*
        if($task && $task->getBoard()){
            $taskService = $this->container->get("flower.core.service.task");
            $taskService->massiveUpdate($task->getBoard());
        }
        */
    }
}