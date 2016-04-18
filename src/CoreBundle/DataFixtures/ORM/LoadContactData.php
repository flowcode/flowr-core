<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Flower\ModelBundle\Entity\Clients\Contact;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Clients\Account;

/**
 * Description of LoadAccountData
 *
 * @author Juan Manuel Aguero <jaguero@flowcode.com.ar>
 */
class LoadContactData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $contact = new Contact();
        $contact->addAccount($this->getReference('account-coca-cola'));
        $contact->setFirstname("First Contact");
        $contact->setAllowCampaignMail(false);
        $contact->setLastname("Lastname");
        $contact->setSource($this->getReference('contact-source-social2'));

        $manager->persist($contact);

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
