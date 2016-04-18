<?php

namespace Flower\CoreBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Description of SearchService
 *
 * @author Francisco Memoli <fmemoli@flowcode.com.ar>
 */
class SearchService implements ContainerAwareInterface
{

    /**
     * @var Container
     */
    private $container;
    private $context;

    public function __construct(SecurityContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
        $this->em = $this->container->get("doctrine.orm.entity_manager");
    }

    public function pareceSearch($searchText)
    {
        $explode = explode(" ", $searchText);
        $result = array('task' => array("complete" => $searchText, "elements" => $explode),
            'project' => array("complete" => $searchText, "elements" => $explode),
            'account' => array("complete" => $searchText, "elements" => $explode),
            'contact' => array("complete" => $searchText, "elements" => $explode),
            'event' => array("complete" => $searchText, "elements" => $explode),
            'note' => array("complete" => $searchText, "elements" => $explode),);
        return $result;
    }

    private function getUser()
    {
        return $this->context->getToken()->getUser();
    }

    public function searchTasks($completeText, $searchText)
    {
        $em = $this->em;
        return $em->getRepository('FlowerModelBundle:Board\Task')->search($completeText, $searchText);

    }

    public function searchContacts($completeText, $searchText)
    {
        $em = $this->em;
        $accountAlias = "a";
        $contactAlias = "c";
        $repository = $em->getRepository('FlowerModelBundle:Clients\Contact');
        $qb = $repository->createQueryBuilder($contactAlias);
        if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $qb->join("c.accounts", $accountAlias);
            $secGroupSrv = $this->container->get('user.service.securitygroup');
            $qb = $secGroupSrv->addSecurityGroupFilter($qb, $this->getUser(), $accountAlias);
        }
        return $repository->search($qb, $completeText, $searchText);
    }

    public function searchAccounts($completeText, $searchText)
    {
        $em = $this->em;
        $accountAlias = "a";
        $repository = $em->getRepository('FlowerModelBundle:Clients\Account');
        $qb = $repository->createQueryBuilder($accountAlias);
        //if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        //    $secGroupSrv = $this->container->get('user.service.securitygroup');
        //    $qb = $secGroupSrv->addSecurityGroupFilter($qb, $this->getUser(), $accountAlias);
        //}
        return $repository->search($qb, $completeText, $searchText);
    }

    public function searchProjects($completeText, $searchText)
    {
        $em = $this->em;
        return $em->getRepository('FlowerModelBundle:Project\Project')->search($completeText, $searchText);

    }

    public function searchNotes($completeText, $searchText)
    {
        $em = $this->em;

        return $em->getRepository('FlowerModelBundle:Clients\Note')->search($completeText, $searchText);

    }

    public function searchEvents($completeText, $searchText)
    {
        $em = $this->em;
        $today = new \DateTime();
        $today->sub(new \DateInterval('PT1H'));
        return $em->getRepository('FlowerModelBundle:Planner\Event')->search($completeText, $searchText, $today, $this->getUser());
    }
}
