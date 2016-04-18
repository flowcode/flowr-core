<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadUserData
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $userManager = $this->container->get('fos_user.user_manager');

        /* admin */
        $userAdmin = $userManager->createUser();
        $userAdmin->setUsername("flower-admin");
        $userAdmin->setEmail("flower-admin@mail.com");
        $userAdmin->setEnabled(true);
        $userAdmin->setPlainPassword("Maipu671");

        $userAdmin->addRole("ROLE_ADMIN");

        $this->addReference('user_flower_admin', $userAdmin);

        $userManager->updateUser($userAdmin);

        /* users */
        /*1*/
        $userAdmin1 = $userManager->createUser();
        //$userAdmin1 = new \Flower\ModelBundle\Entity\User();
        $userAdmin1->setUsername("admin-acme");
        $userAdmin1->setEmail("admin-acme@mail.com");
        $userAdmin1->setEnabled(true);
        $userAdmin1->setPlainPassword("Maipu671");
        $userAdmin1->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_admin_1', $userAdmin1);
        $userManager->updateUser($userAdmin1);
        /*2*/
        $userAdmin2 = $userManager->createUser();
        $userAdmin2->setUsername("admin-duff");
        $userAdmin2->setEmail("admin-duff@mail.com");
        $userAdmin2->setEnabled(true);
        $userAdmin2->setPlainPassword("Maipu671");
        $userAdmin2->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_admin_2', $userAdmin2);
        $userManager->updateUser($userAdmin2);
        /*3*/
        $userAdmin3 = $userManager->createUser();
        $userAdmin3->setUsername("user-3");
        $userAdmin3->setEmail("user-3@mail.com");
        $userAdmin3->setEnabled(true);
        $userAdmin3->setPlainPassword("Maipu671");
        $userAdmin3->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_3', $userAdmin3);
        $userManager->updateUser($userAdmin3);
        /*4*/
        $userAdmin4 = $userManager->createUser();
        $userAdmin4->setUsername("user-4");
        $userAdmin4->setEmail("user-4@mail.com");
        $userAdmin4->setEnabled(true);
        $userAdmin4->setPlainPassword("Maipu671");
        $userAdmin4->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_4', $userAdmin4);
        $userManager->updateUser($userAdmin4);
        /*5*/
        $userAdmin5 = $userManager->createUser();
        $userAdmin5->setUsername("user-5");
        $userAdmin5->setEmail("user-5@mail.com");
        $userAdmin5->setEnabled(true);
        $userAdmin5->setPlainPassword("Maipu671");
        $userAdmin5->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_5', $userAdmin5);
        $userManager->updateUser($userAdmin5);
        /*6*/
        $userAdmin6 = $userManager->createUser();
        $userAdmin6->setUsername("user-6");
        $userAdmin6->setEmail("user-6@mail.com");
        $userAdmin6->setEnabled(true);
        $userAdmin6->setPlainPassword("Maipu671");
        $userAdmin6->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_6', $userAdmin6);
        $userManager->updateUser($userAdmin6);
        /*7*/
        $userAdmin7 = $userManager->createUser();
        $userAdmin7->setUsername("user-7");
        $userAdmin7->setEmail("user-7@mail.com");
        $userAdmin7->setEnabled(true);
        $userAdmin7->setPlainPassword("Maipu671");
        $userAdmin7->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_7', $userAdmin7);
        $userManager->updateUser($userAdmin7);
        /*8*/
        $userAdmin8 = $userManager->createUser();
        $userAdmin8->setUsername("user-8");
        $userAdmin8->setEmail("user-8@mail.com");
        $userAdmin8->setEnabled(true);
        $userAdmin8->setPlainPassword("Maipu671");
        $userAdmin8->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_8', $userAdmin8);
        $userManager->updateUser($userAdmin8);
        /*9*/
        $userAdmin9 = $userManager->createUser();
        $userAdmin9->setUsername("user-9");
        $userAdmin9->setEmail("user-9@mail.com");
        $userAdmin9->setEnabled(true);
        $userAdmin9->setPlainPassword("Maipu671");
        $userAdmin9->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_9', $userAdmin9);
        $userManager->updateUser($userAdmin9);
        /*10*/
        $userAdmin10 = $userManager->createUser();
        $userAdmin10->setUsername("user-10");
        $userAdmin10->setEmail("user-10@mail.com");
        $userAdmin10->setEnabled(true);
        $userAdmin10->setPlainPassword("Maipu671");
        $userAdmin10->addGroup($this->getReference('group-smartdrink'));
        $this->addReference('user_10', $userAdmin10);
        $userManager->updateUser($userAdmin10);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 2;
    }

}
