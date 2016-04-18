<?php

namespace Flower\CoreBundle\Controller\privateApi;

use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * search controller.
 *
 * @Route("/p/api/search")
 */
class SearchController extends FOSRestController
{
    /**
     * Lists all Notes entities.
     *
     * @Route("/", name="search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $searchText = $request->query->get('search');

        $searchService = $this->get('flower.search');

        $searchArray = $searchService->pareceSearch($searchText);
        $tasks = $searchService->searchTasks($searchArray["task"]["complete"], $searchArray["task"]["elements"]);
        $contacts = $searchService->searchContacts($searchArray["contact"]["complete"], $searchArray["contact"]["elements"]);
        $notes = $searchService->searchNotes($searchArray["note"]["complete"], $searchArray["note"]["elements"]);
        $events = $searchService->searchEvents($searchArray["event"]["complete"], $searchArray["event"]["elements"]);
        $accounts = $searchService->searchAccounts($searchArray["account"]["complete"], $searchArray["account"]["elements"]);
        $projects = $searchService->searchProjects($searchArray["project"]["complete"], $searchArray["project"]["elements"]);
        $response = array("tasks"=> $tasks, "projects" => $projects, "contacts" => $contacts, "accounts" => $accounts, "events" => $events, "notes" => $notes);
        $view = FOSView::create($response, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('search'));
        return $this->handleView($view);
    }

}
