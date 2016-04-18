<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Flower\ModelBundle\Entity\Planner\EventStatus;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadEventStatusData
 *
 * @author Pedro Barri <pbarri@flowcode.com.ar>
 */
class LoadEventStatusData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $eventStatusBacklog = new EventStatus();
        $eventStatusBacklog->setName(EventStatus::STATUS_PENDING);
        $eventStatusBacklog->setDescription("Pending event.");
        $manager->persist($eventStatusBacklog);

        $this->addReference('event_status_backlog', $eventStatusBacklog);
        
        $eventStatusTodo = new EventStatus();
        $eventStatusTodo->setName(EventStatus::STATUS_RESCHEDULE);
        $eventStatusTodo->setDescription("Reschedule event.");
        $manager->persist($eventStatusTodo);

        $this->addReference('event_status_todo', $eventStatusTodo);

        $eventStatusDone = new EventStatus();
        $eventStatusDone->setName(EventStatus::STATUS_DONE);
        $eventStatusDone->setDescription("Done.");
        $manager->persist($eventStatusDone);

        $this->addReference('event_status_done', $eventStatusDone);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 6;
    }

}
