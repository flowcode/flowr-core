<?php

namespace Flower\CoreBundle\Controller\privateApi;

use Doctrine\ORM\QueryBuilder;
use Flower\ModelBundle\Entity\Clients\Account;
use Flower\ModelBundle\Entity\Clients\Note;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Notes controller.
 *
 * @Route("/p/api/notes")
 */
class NoteController extends FOSRestController
{
    /**
     * Lists all Notes entities.
     *
     * @Route("/account/{account}/{page}", name="account_notes", defaults={"page" = 0})
     * @Method("GET")
     */
    public function notesAction(Account $account,$page)
    {
        $limit = 4;
        $em = $this->getDoctrine()->getManager();
        $notes = $em->getRepository('FlowerModelBundle:Clients\Note')->findBy( array('account' => $account),
                                                            array('created' => 'desc'),
                                                            $limit,
                                                            $page*$limit);
        $total = count($account->getNotes());
        $response = array("notes"=> $notes, "total" => $total,"page" => $page, "limit" => $limit);
        $view = FOSView::create($response, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('search'));
        return $this->handleView($view);
    }
    /**
     * create new note
     *
     * @Route("/account/{account}", name="account_create_note")
     * @Method("POST")
     */
    public function createNoteAction(Request $request, Account $account)
    {
        $em = $this->getDoctrine()->getManager();
        $title = $request->get("title");
        $body = $request->get("body");
        if ($title) {
            $note = new Note();
            $note->setTitle($title);
            $note->setAccount($account);
            $note->setBody($body);
            $em->persist($note);
            $em->flush();
            $view = FOSView::create($note, Codes::HTTP_OK)->setFormat('json');
            $view->getSerializationContext()->setGroups(array('search'));
            return $this->handleView($view);
        }

        return new JsonResponse(array("message" => "invalid"), 500);
    }
    /**
     * create new note
     *
     * @Route("/{note}", name="account_update_note")
     * @Method("PUT")
     */
    public function updateNoteAction(Request $request, Note $note)
    {
        $em = $this->getDoctrine()->getManager();
        $title = $request->get("title");
        $body = $request->get("body");
        if ($title && $body) {
            $note->setTitle($title);
            $note->setBody($body);
            $em->flush();
            $view = FOSView::create($note, Codes::HTTP_OK)->setFormat('json');
            $view->getSerializationContext()->setGroups(array('search'));
            return $this->handleView($view);
        }

        return new JsonResponse(array("message" => "invalid"), 500);
    }
}
