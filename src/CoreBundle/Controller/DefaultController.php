<?php

namespace Flower\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Flower\ModelBundle\Entity\Board\TaskStatus;

class DefaultController extends Controller
{

    /**
     * Lists all Activity entities.
     *
     * @Route("/", name="dashboard")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $taskRepo = $em->getRepository('FlowerModelBundle:Board\Task');
        $taskStatusTodo = $em->getRepository('FlowerModelBundle:Board\TaskStatus')->findOneBy(array("name" => TaskStatus::STATUS_TODO));
        $taskStatusDoing = $em->getRepository('FlowerModelBundle:Board\TaskStatus')->findOneBy(array("name" => TaskStatus::STATUS_DOING));
        $CampaignMailRepo = $em->getRepository('FlowerModelBundle:Marketing\CampaignMail');

        $activeCampaignMail = $CampaignMailRepo->getCountEnabled();

        $statuses = array($taskStatusTodo->getId(), $taskStatusDoing->getId());
        $assigneeId = $this->getUser()->getId();
        $limit = 5;
        $myTasks = $taskRepo->findByStatusByPos($statuses, null, $assigneeId, $limit);

        $limit = 10;
        $page = 0;
        $today = new \DateTime();
        $today->sub(new \DateInterval('PT1H'));
        $tomorrow = new \DateTime('tomorrow');
        $tomorrow2 = new \DateTime('tomorrow');
        $tomorrow2->add(new \DateInterval('P1D'));
        $eventtoday = $em->getRepository('FlowerModelBundle:Planner\Event')->findByStartDate($this->getUser(), $today, $tomorrow, $limit, $page * $limit);
        $eventstomorrow = $em->getRepository('FlowerModelBundle:Planner\Event')->findByStartDate($this->getUser(), $tomorrow, $tomorrow2, $limit, $page * $limit);

        /* activity feed */
        $activityFeed = $this->get('board.service.history')->getUserActivity($this->getUser());


        return $this->render('FlowerCoreBundle:Default:index.html.twig', array(
            "active_campaign_mail" => $activeCampaignMail,
            "myTasks" => $myTasks,
            "eventstoday" => $eventtoday,
            "eventstomorrow" => $eventstomorrow,
            "feed" => $activityFeed,
        ));
    }

    /**
     * Lists all Activity entities.
     * @Template()
     */
    public function appsAction()
    {
        return array();
    }


    /**
     * Lists all Activity entities.
     *
     * @Route("/admin", name="admin_dash")
     * @Method("GET")
     * @Template()
     */
    public function indexAdminAction()
    {
        return array();
    }
}
