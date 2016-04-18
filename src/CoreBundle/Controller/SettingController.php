<?php

namespace Flower\CoreBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Flower\CoreBundle\Form\Type\SettingType;
use Flower\ModelBundle\Entity\Setting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Setting controller.
 *
 * @Route("/setting")
 */
class SettingController extends Controller
{

    /**
     * Lists all Setting entities.
     *
     * @Route("/", name="setting")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerModelBundle:Setting')->createQueryBuilder('s');
        $this->addQueryBuilderSort($qb, 'setting');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);

        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Setting entity.
     *
     * @Route("/{id}/show", name="setting_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Setting $setting)
    {
        $deleteForm = $this->createDeleteForm($setting->getId(), 'setting_delete');

        return array(
            'setting' => $setting,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Setting entity.
     *
     * @Route("/new", name="setting_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $setting = new Setting();
        $form = $this->createForm(new SettingType(), $setting);

        return array(
            'setting' => $setting,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Setting entity.
     *
     * @Route("/create", name="setting_create")
     * @Method("POST")
     * @Template("FlowerCoreBundle:Setting:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $setting = new Setting();
        $form = $this->createForm(new SettingType(), $setting);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($setting);
            $em->flush();

            return $this->redirect($this->generateUrl('setting_show', array('id' => $setting->getId())));
        }

        return array(
            'setting' => $setting,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Setting entity.
     *
     * @Route("/{id}/edit", name="setting_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Setting $setting)
    {
        $editForm = $this->createForm(new SettingType(), $setting, array(
            'action' => $this->generateUrl('setting_update', array('id' => $setting->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($setting->getId(), 'setting_delete');

        return array(
            'setting' => $setting,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Setting entity.
     *
     * @Route("/{id}/update", name="setting_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerCoreBundle:Setting:edit.html.twig")
     */
    public function updateAction(Setting $setting, Request $request)
    {
        $editForm = $this->createForm(new SettingType(), $setting, array(
            'action' => $this->generateUrl('setting_update', array('id' => $setting->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('setting_show', array('id' => $setting->getId())));
        }
        $deleteForm = $this->createDeleteForm($setting->getId(), 'setting_delete');

        return array(
            'setting' => $setting,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="setting_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('setting', $field, $type);

        return $this->redirect($this->generateUrl('setting'));
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
     * Deletes a Setting entity.
     *
     * @Route("/{id}/delete", name="setting_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Setting $setting, Request $request)
    {
        $form = $this->createDeleteForm($setting->getId(), 'setting_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($setting);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('setting'));
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
