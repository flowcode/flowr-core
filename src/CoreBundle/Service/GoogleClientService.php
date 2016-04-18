<?php

namespace Flower\CoreBundle\Service;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Flower\ModelBundle\Entity\Board\Task;
/**
 * Description of TaskService
 */
class GoogleClientService implements ContainerAwareInterface
{
	/**
     * @var Container
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
        $this->em = $this->container->get("doctrine.orm.entity_manager");
    }

    public function getClient()
    {
        $client = new \Google_Client();

        $client->setAuthConfigFile($this->container->get('kernel')->getRootDir().'client_secrets.json');
        $client->addScope(\Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');

        $client->setApplicationName("Flowr");
        $client->setDeveloperKey("AIzaSyCUdsGGDslZSu-Cv0mU-C9oCjryOM0RAbc");
        return $client;
    }


}

       