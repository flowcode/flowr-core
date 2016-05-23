<?php

namespace Flower\CoreBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Flower\CoreBundle\Form\Type\NotificationChannelImplType;
use Flower\CoreBundle\Entity\NotificationChannelImpl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * NotificationChannelImpl controller.
 *
 * @Route("/admin/notification_channel")
 */
class NotificationChannelImplController extends Controller
{

    /**
     * Lists all NotificationChannelImpl entities.
     *
     * @Route("/", name="notification_channel")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerCoreBundle:NotificationChannelImpl')->createQueryBuilder('t');
        $this->addQueryBuilderSort($qb, 'tracker');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);

        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a NotificationChannelImpl entity.
     *
     * @Route("/{id}/show", name="notification_channel_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(NotificationChannelImpl $notificationChannelImpl)
    {
        $deleteForm = $this->createDeleteForm($notificationChannelImpl->getId(), 'tracker_delete');

        return array(
            'notificationChannel' => $notificationChannelImpl,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new NotificationChannelImpl entity.
     *
     * @Route("/new", name="notification_channel_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $tracker = new NotificationChannelImpl();
        $form = $this->createForm(new NotificationChannelImplType(), $tracker);

        return array(
            'tracker' => $tracker,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new NotificationChannelImpl entity.
     *
     * @Route("/create", name="notification_channel_create")
     * @Method("POST")
     * @Template("FlowerCoreBundle:NotificationChannelImpl:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $tracker = new NotificationChannelImpl();
        $form = $this->createForm(new NotificationChannelImplType(), $tracker);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tracker);
            $em->flush();

            return $this->redirect($this->generateUrl('notification_channel_show', array('id' => $tracker->getId())));
        }

        return array(
            'tracker' => $tracker,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing NotificationChannelImpl entity.
     *
     * @Route("/{id}/edit", name="notification_channel_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(NotificationChannelImpl $tracker)
    {
        $editForm = $this->createForm(new NotificationChannelImplType(), $tracker, array(
            'action' => $this->generateUrl('notification_channel_update', array('id' => $tracker->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($tracker->getId(), 'notification_channel_delete');

        return array(
            'tracker' => $tracker,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing NotificationChannelImpl entity.
     *
     * @Route("/{id}/update", name="notification_channel_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerCoreBundle:NotificationChannelImpl:edit.html.twig")
     */
    public function updateAction(NotificationChannelImpl $tracker, Request $request)
    {
        $editForm = $this->createForm(new NotificationChannelImplType(), $tracker, array(
            'action' => $this->generateUrl('notification_channel_update', array('id' => $tracker->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('notification_channel_show', array('id' => $tracker->getId())));
        }
        $deleteForm = $this->createDeleteForm($tracker->getId(), 'notification_channel_delete');

        return array(
            'tracker' => $tracker,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="notification_channel_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('tracker', $field, $type);

        return $this->redirect($this->generateUrl('tracker'));
    }

    /**
     * @param string $name session name
     * @param string $field field name
     * @param string $type sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder($name)
    {
        $session = $this->getRequest()->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Deletes a NotificationChannelImpl entity.
     *
     * @Route("/{id}/delete", name="notification_channel_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(NotificationChannelImpl $tracker, Request $request)
    {
        $form = $this->createDeleteForm($tracker->getId(), 'tracker_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tracker);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tracker'));
    }

    /**
     * Create Delete form
     *
     * @param integer $id
     * @param string $route
     * @return Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm();
    }

}
