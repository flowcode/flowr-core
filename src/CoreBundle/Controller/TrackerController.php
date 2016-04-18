<?php

namespace Flower\CoreBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Flower\CoreBundle\Form\Type\TrackerType;
use Flower\ModelBundle\Entity\Tracker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tracker controller.
 *
 * @Route("/tracker")
 */
class TrackerController extends Controller
{

    /**
     * Lists all Tracker entities.
     *
     * @Route("/", name="tracker")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerModelBundle:Tracker')->createQueryBuilder('t');
        $this->addQueryBuilderSort($qb, 'tracker');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);

        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Tracker entity.
     *
     * @Route("/{id}/show", name="tracker_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Tracker $tracker)
    {
        $deleteForm = $this->createDeleteForm($tracker->getId(), 'tracker_delete');

        return array(
            'tracker' => $tracker,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Tracker entity.
     *
     * @Route("/new", name="tracker_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $tracker = new Tracker();
        $form = $this->createForm(new TrackerType(), $tracker);

        return array(
            'tracker' => $tracker,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Tracker entity.
     *
     * @Route("/create", name="tracker_create")
     * @Method("POST")
     * @Template("FlowerCoreBundle:Tracker:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $tracker = new Tracker();
        $form = $this->createForm(new TrackerType(), $tracker);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tracker);
            $em->flush();

            return $this->redirect($this->generateUrl('tracker_show', array('id' => $tracker->getId())));
        }

        return array(
            'tracker' => $tracker,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tracker entity.
     *
     * @Route("/{id}/edit", name="tracker_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Tracker $tracker)
    {
        $editForm = $this->createForm(new TrackerType(), $tracker, array(
            'action' => $this->generateUrl('tracker_update', array('id' => $tracker->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($tracker->getId(), 'tracker_delete');

        return array(
            'tracker' => $tracker,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tracker entity.
     *
     * @Route("/{id}/update", name="tracker_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerCoreBundle:Tracker:edit.html.twig")
     */
    public function updateAction(Tracker $tracker, Request $request)
    {
        $editForm = $this->createForm(new TrackerType(), $tracker, array(
            'action' => $this->generateUrl('tracker_update', array('id' => $tracker->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('tracker_show', array('id' => $tracker->getId())));
        }
        $deleteForm = $this->createDeleteForm($tracker->getId(), 'tracker_delete');

        return array(
            'tracker' => $tracker,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="tracker_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('tracker', $field, $type);

        return $this->redirect($this->generateUrl('tracker'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
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
     * @param string       $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Deletes a Tracker entity.
     *
     * @Route("/{id}/delete", name="tracker_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Tracker $tracker, Request $request)
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
     * @param integer                       $id
     * @param string                        $route
     * @return Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
                        ->setAction($this->generateUrl($route, array('id' => $id)))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
