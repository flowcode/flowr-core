<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Clients\OpportunityStatus;

/**
 * Description of LoadOpportunityStatusData
 *
 * @author Pedro Barri <pbarri@flowcode.com.ar>
 */
class LoadOpportunityStatusData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $OpportunityStatus = new OpportunityStatus();
        $OpportunityStatus->setName("status_pending");
        $OpportunityStatus->setWon(false);
        $manager->persist($OpportunityStatus);

        $OpportunityStatus1 = new OpportunityStatus();
        $OpportunityStatus1->setName("status_won");
        $OpportunityStatus1->setWon(true);
        $manager->persist($OpportunityStatus1);

        $OpportunityStatus2 = new OpportunityStatus();
        $OpportunityStatus2->setName("status_lost");
        $OpportunityStatus2->setWon(false);
        $manager->persist($OpportunityStatus2);

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
