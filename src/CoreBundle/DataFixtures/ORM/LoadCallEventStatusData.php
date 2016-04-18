<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Clients\CallEventStatus;

/**
 * Description of LoadCallEventStatusData
 *
 * @author Pedro Barri <pbarri@flowcode.com.ar>
 */
class LoadCallEventStatusData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $callEventStatus = new CallEventStatus();
        $callEventStatus->setName("Pendiente");
        $callEventStatus->setFinished(false);
        $manager->persist($callEventStatus);

        $this->addReference('callEvent-pending', $callEventStatus);

        $callEventStatus1 = new CallEventStatus();
        $callEventStatus1->setName("Finalizada");
        $callEventStatus1->setFinished(true);
        $manager->persist($callEventStatus1);

        $this->addReference('callEvent-done', $callEventStatus1);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 9;
    }

}
