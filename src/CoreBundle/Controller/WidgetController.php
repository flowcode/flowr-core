<?php

namespace Flower\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Flower\ModelBundle\Entity\Board\TaskStatus;

class WidgetController extends Controller
{


    /**
     * Lists all Activity entities.
     * @Template()
     */
    public function impersonateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('FlowerModelBundle:User\User')->findAll();
        return array(
            'users' => $users,
        );
    }

}
