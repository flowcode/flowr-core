<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadUserGroupData
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class LoadUserGroupData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $groupManager = $this->container->get('fos_user.group_manager');

        $group = $groupManager->createGroup("Smartdrink");

        $group->setName("Smartdrink");
        $group->addRole("ROLE_CLIENT");
        $group->addRole("ROLE_ACCOUNT");
        $group->addRole("ROLE_TASK");
        $group->addRole("ROLE_BOARD");
        $group->addRole("ROLE_ESTIMATIONS");
        $group->addRole("ROLE_PLANNER");
        $group->addRole("ROLE_CALL");
        $group->addRole("ROLE_OPORTUNITIES");

        $manager->persist($group);

        $this->addReference('group-smartdrink', $group);

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
