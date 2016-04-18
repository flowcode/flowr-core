<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Flower\ModelBundle\Entity\User\SecurityGroup;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadUserGroupData
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class LoadSecurityGroupData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $secGroup1 = new SecurityGroup();
        $secGroup1->setName("sec group 1");

        $manager->persist($secGroup1);
        $this->addReference('sec-group-1', $secGroup1);

        $secGroup2 = new SecurityGroup();
        $secGroup2->setName("sec group 2");

        $manager->persist($secGroup2);
        $this->addReference('sec-group-2', $secGroup2);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 1;
    }

}
