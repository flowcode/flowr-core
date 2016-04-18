<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Flower\ModelBundle\Entity\Board\Tracker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadUserData
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class LoadTrackerData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {



        $trackerAdmin = new Tracker();
        $trackerAdmin->setName("administration");
        $trackerAdmin->setDescription("Admin hours");
        $manager->persist($trackerAdmin);

        $this->addReference('tracker_admin', $trackerAdmin);

        $trackerDevelopment = new Tracker();
        $trackerDevelopment->setName("development");
        $trackerDevelopment->setDescription("Development hours");
        $manager->persist($trackerDevelopment);

        $this->addReference('tracker_development', $trackerDevelopment);

        $trackerTesting = new Tracker();
        $trackerTesting->setName("testing");
        $trackerTesting->setDescription("Testing hours");
        $manager->persist($trackerTesting);

        $this->addReference('tracker_testing', $trackerTesting);

        $trackerSupport = new Tracker();
        $trackerSupport->setName("support");
        $trackerSupport->setDescription("Support hours");
        $manager->persist($trackerSupport);

        $this->addReference('tracker_support', $trackerSupport);


        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 5;
    }

}
