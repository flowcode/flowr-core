<?php

namespace Flower\CoreBundle\Controller\Webhook;

use Doctrine\ORM\QueryBuilder;
use Flower\ModelBundle\Entity\Planner\Event;
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
 * @Route("/webhook/default")
 */
class WebhookDefaultController extends FOSRestController
{
    /**
     * Lists all Notes entities.
     *
     * @Route("/event/create", name="webhook_default_event_create")
     * @Method("POST")
     */
    public function createEventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('FlowerModelBundle:User\User')->findOneBy(array('username' => $request->get("user_username")));

        $event = new Event();
        $event->setOwner($user);
        $event->setVisible(0);

        $event->setTitle($request->get("title"));
        $event->setStartDate(new \DateTime($request->get("start_date")));
        $event->setEndDate(new \DateTime($request->get("end_date")));

        $em->persist($event);
        $em->flush();

        $response = array(
            "message" => "done"
        );

        $view = FOSView::create($response, Codes::HTTP_OK)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * Lists all Notes entities.
     *
     * @Route("/event/mines", name="webhook_default_event_mines")
     * @Method("GET")
     * @Template()
     */
    public function myEventsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('FlowerModelBundle:User\User')->findOneBy(array('username' => $request->get("user_username")));

        $today = new \DateTime();
        $nextDays = clone($today);
        $nextDays->modify('+6 days');

        $events = $em->getRepository('FlowerModelBundle:Planner\Event')->findByStartDate($user, $today, $nextDays, 50, 0);

        return array(
            "events" => $events,
        );
    }

}
