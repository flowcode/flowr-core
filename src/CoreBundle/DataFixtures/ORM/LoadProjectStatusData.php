<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Flower\ModelBundle\Entity\Project\ProjectStatus;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadUserData
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class LoadProjectStatusData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $statusBacklog = new ProjectStatus();
        $statusBacklog->setName(ProjectStatus::STATUS_BACKLOG);
        $statusBacklog->setDescription("Projects in backlog");
        $manager->persist($statusBacklog);

        $this->addReference('project_status_backlog', $statusBacklog);

        $statusInProgress = new ProjectStatus();
        $statusInProgress->setName(ProjectStatus::STATUS_IN_PROGRESS);
        $statusInProgress->setDescription("Projects in progress");
        $manager->persist($statusInProgress);

        $this->addReference('project_status_in_progress', $statusInProgress);

        $statusTesting = new ProjectStatus();
        $statusTesting->setName(ProjectStatus::STATUS_TESTING);
        $statusTesting->setDescription("Projects in testing");
        $manager->persist($statusTesting);

        $this->addReference('project_status_testing', $statusTesting);

        $statusFinished = new ProjectStatus();
        $statusFinished->setName(ProjectStatus::STATUS_FINISHED);
        $statusFinished->setDescription("Projects that are finished");
        $manager->persist($statusFinished);

        $this->addReference('project_status_finished', $statusFinished);


        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 3;
    }

}
