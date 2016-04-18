<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Flower\ModelBundle\Entity\Board\TaskStatus;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadUserData
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class LoadTaskStatusData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $statusBacklog = new TaskStatus();
        $statusBacklog->setName(TaskStatus::STATUS_BACKLOG);
        $statusBacklog->setDescription("Task in the backlog.");
        $manager->persist($statusBacklog);

        $this->addReference('task_status_backlog', $statusBacklog);
        
        $statusTodo = new TaskStatus();
        $statusTodo->setName(TaskStatus::STATUS_TODO);
        $statusTodo->setDescription("Task to do.");
        $manager->persist($statusTodo);

        $this->addReference('task_status_todo', $statusTodo);

        $statusDoing = new TaskStatus();
        $statusDoing->setName(TaskStatus::STATUS_DOING);
        $statusDoing->setDescription("Doing task");
        $manager->persist($statusDoing);

        $this->addReference('task_status_doing', $statusDoing);

        $statusDone = new TaskStatus();
        $statusDone->setName(TaskStatus::STATUS_DONE);
        $statusDone->setDescription("Done");
        $manager->persist($statusDone);

        $this->addReference('task_status_done', $statusDone);
        
        $statusClosed = new TaskStatus();
        $statusClosed->setName(TaskStatus::STATUS_CLOSED);
        $statusClosed->setDescription("Closed");
        $manager->persist($statusClosed);

        $this->addReference('task_status_closed', $statusClosed);


        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 4;
    }

}
