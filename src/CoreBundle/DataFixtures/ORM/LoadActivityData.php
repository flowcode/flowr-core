<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Clients\Activity;

/**
 * Description of LoadActivityData
 *
 * @author Pedro Barri <pbarri@flowcode.com.ar>
 */
class LoadActivityData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $activity = new Activity();
        $activity->setName("Software Boutique");
        $manager->persist($activity);

        $this->addReference('activity-web', $activity);

        $activity1 = new Activity();
        $activity1->setName("Venta de chupetes");
        $manager->persist($activity1);

        $this->addReference('activity-chupet', $activity1);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 7;
    }

}
