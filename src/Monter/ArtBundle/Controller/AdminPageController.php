<?php

namespace Monter\ArtBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Monter\ArtBundle\Entity\Page;
use Monter\ArtBundle\Form\PageType;

/**
 * Page controller.
 *
 * @Route("/admin/page")
 */
class AdminPageController extends Controller
{

    /**
     * Lists all Page entities.
     *
     * @Route("/", name="admin_page")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->createQuery('select p from MonterArtBundle:Page p where p.parentPage is null')->getResult();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Page entity.
     *
     * @Route("/{parentPageId}", name="admin_page_create")
     * @Method("POST")
     * @Template("MonterArtBundle:AdminPage:new.html.twig")
     */
    public function createAction(Request $request, $parentPageId)
    {
        $entity = new Page();
        $form = $this->createCreateForm($entity, $parentPageId);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_page_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Page entity.
    *
    * @param Page $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Page $entity, $parentPageId)
    {
        $form = $this->createForm(new PageType($parentPageId), $entity, array(
            'action' => $this->generateUrl('admin_page_create', array('parentPageId' => $parentPageId)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Vytvor', 'attr' => array('class' => 'btn btn-primary col-sm-offset-4')));

        return $form;
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @Route("/new/{parentPageId}", name="admin_page_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($parentPageId)
    {
        $entity = new Page();
        $form   = $this->createCreateForm($entity, $parentPageId);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/{id}/edit", name="admin_page_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MonterArtBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Page entity.
    *
    * @param Page $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Page $entity)
    {
        $form = $this->createForm(new PageType(), $entity, array(
            'action' => $this->generateUrl('admin_page_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Uprav', 'attr' => array('class' => 'btn btn-primary col-sm-offset-4')));

        return $form;
    }
    /**
     * Edits an existing Page entity.
     *
     * @Route("/{id}", name="admin_page_update")
     * @Method("PUT")
     * @Template("MonterArtBundle:AdminPage:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MonterArtBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_page_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a Page entity.
     *
     * @Route("/{id}", name="admin_page_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MonterArtBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_page'));
    }

    /**
     * @Route("/get-page-content/{url}", defaults={"url" : "index"} ,name="admin_get_page_content")
     * @Method("GET")
     */
    public function getPageContentAction($url)
    {
        $page = $this->getDoctrine()->getManager()->getRepository('MonterArtBundle:Page')->findOneBy(array('urlKey' => $url));
        return $this->render('MonterArtBundle:AdminPage:getPageContent.html.twig', array( 'page' => $page ));
    }
}
