<?php

namespace Flower\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Flower\ModelBundle\Entity\User\OrganizationSetting;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Clients\CallEvent;

/**
 * Description of LoadCallEventData
 *
 * @author Pedro Barri <pbarri@flowcode.com.ar>
 */
class LoadSettingOrganizationData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {

        $orgSetting = new OrganizationSetting();
        $orgSetting->setName(OrganizationSetting::logo);
        $orgSetting->setType(OrganizationSetting::type_file_image);
        $orgSetting->setValue("uploads/default.png");
        $manager->persist($orgSetting);

        $orgSetting2 = new OrganizationSetting();
        $orgSetting2->setName(OrganizationSetting::org_title);
        $orgSetting2->setType(OrganizationSetting::type_string);
        $orgSetting2->setValue("My Company");
        $manager->persist($orgSetting2);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 11;
    }

}
