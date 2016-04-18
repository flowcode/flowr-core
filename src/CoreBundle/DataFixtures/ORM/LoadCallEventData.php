<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Clients\CallEvent;

/**
 * Description of LoadCallEventData
 *
 * @author Pedro Barri <pbarri@flowcode.com.ar>
 */
class LoadCallEventData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $callEvent = new CallEvent();
        $callEvent->setSubject("Nuevo negocio");
        $callEvent->setAccount($this->getReference('account-flowcode'));
        $callEvent->setDate(new \DateTime());
        $callEvent->setStatus($this->getReference('callEvent-pending'));
        $callEvent->setAssignee($this->getReference('user_admin_1'));
        $manager->persist($callEvent);

        $this->addReference('callEvent-web', $callEvent);

        $callEvent1 = new CallEvent();
        $callEvent1->setSubject("Software Boutique");
        $callEvent1->setAccount($this->getReference('account-flower'));
        $callEvent1->setDate(new \DateTime());
        $callEvent1->setStatus($this->getReference('callEvent-pending'));
        $callEvent1->setAssignee($this->getReference('user_admin_2'));
        $manager->persist($callEvent1);

        $this->addReference('callEvent-chupet', $callEvent1);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 10;
    }

}
