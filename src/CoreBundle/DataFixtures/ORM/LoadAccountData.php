<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Clients\Account;

/**
 * Description of LoadAccountData
 *
 * @author Pedro Barri <pbarri@flowcode.com.ar>
 */
class LoadAccountData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        /*1*/
        $account = new Account();
        $account->setName("Flowcode");
        $account->setBusinessName("Flowcode");
        $account->setPhone("11-4948-4444");
        $account->setAddress("Aquella 123");
        $account->setCuit("27343536357");
        $account->setActivity($this->getReference('activity-web'));
        $account->setAssignee($this->getReference('user_admin_1'));
        $manager->persist($account);
        $this->addReference('account-flowcode', $account);
        /*2*/
        $account2 = new Account();
        $account2->setName("Flower");
        $account2->setBusinessName("Flower");
        $account2->setPhone("223-7777-9999");
        $account2->setAddress("La otra 456");
        $account2->setCuit("27555566661");
        $account2->setActivity($this->getReference('activity-chupet'));
        $account2->setAssignee($this->getReference('user_admin_2'));
        $manager->persist($account2);
        $this->addReference('account-flower', $account2);
        /*3*/
        $account3 = new Account();
        $account3->setName("Coca-cola");
        $account3->setBusinessName("Coca-cola");
        $account3->setPhone("223-47999912");
        $account3->setAddress("calle 3");
        $account3->setCuit("27555566662");
        $account3->setActivity($this->getReference('activity-chupet'));
        $account3->setAssignee($this->getReference('user_3'));
        $manager->persist($account3);
        $this->addReference('account-coca-cola', $account3);
        /*4*/
        $account4 = new Account();
        $account4->setName("Adidas");
        $account4->setBusinessName("Adidas");
        $account4->setPhone("443-48999913");
        $account4->setAddress("calle 4");
        $account4->setCuit("47555566663");
        $account4->setActivity($this->getReference('activity-chupet'));
        $account4->setAssignee($this->getReference('user_4'));
        $manager->persist($account4);
        $this->addReference('account-adidas', $account4);
        /*5*/
        $account5 = new Account();
        $account5->setName("Nike");
        $account5->setBusinessName("Nike");
        $account5->setPhone("49999914");
        $account5->setAddress("call 5");
        $account5->setCuit("57555566664");
        $account5->setActivity($this->getReference('activity-chupet'));
        $account5->setAssignee($this->getReference('user_5'));
        $manager->persist($account5);
        $this->addReference('account-nike', $account5);
        /*6*/
        $account6 = new Account();
        $account6->setName("Rey del Sabor");
        $account6->setBusinessName("Rey del Sabor");
        $account6->setPhone("663-50999915");
        $account6->setAddress("call 6");
        $account6->setCuit("67555566665");
        $account6->setActivity($this->getReference('activity-chupet'));
        $account6->setAssignee($this->getReference('user_6'));
        $manager->persist($account6);
        $this->addReference('account-rey-del-sabor', $account6);
        /*7*/
        $account7 = new Account();
        $account7->setName("Branca");
        $account7->setBusinessName("Branca");
        $account7->setPhone("773-51999916");
        $account7->setAddress("call 7");
        $account7->setCuit("77555566666");
        $account7->setActivity($this->getReference('activity-chupet'));
        $account7->setAssignee($this->getReference('user_7'));
        $manager->persist($account7);
        $this->addReference('account-branca', $account7);
        /*8*/
        $account8 = new Account();
        $account8->setName("Quilmes");
        $account8->setBusinessName("Quilmes");
        $account8->setPhone("883-52999917");
        $account8->setAddress("call 8");
        $account8->setCuit("87555566667");
        $account8->setActivity($this->getReference('activity-chupet'));
        $account8->setAssignee($this->getReference('user_8'));
        $manager->persist($account8);
        $this->addReference('account-quilmes', $account8);
        /*9*/
        $account9 = new Account();
        $account9->setName("Tetra Pak");
        $account9->setBusinessName("Tetra Pak");
        $account9->setPhone("993-53999918");
        $account9->setAddress("call 9");
        $account9->setCuit("97555566668");
        $account9->setActivity($this->getReference('activity-chupet'));
        $account9->setAssignee($this->getReference('user_9'));
        $manager->persist($account9);
        $this->addReference('account-tetra-pak', $account9);
        /*10*/
        $account10 = new Account();
        $account10->setName("Apple");
        $account10->setBusinessName("Apple");
        $account10->setPhone("10103-54999919");
        $account10->setAddress("call 10");
        $account10->setCuit("107555566669");
        $account10->setActivity($this->getReference('activity-chupet'));
        $account10->setAssignee($this->getReference('user_10'));
        $manager->persist($account10);
        $this->addReference('account-apple', $account10);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 8;
    }

}
