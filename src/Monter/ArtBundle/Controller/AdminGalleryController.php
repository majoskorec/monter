<?php

namespace Monter\ArtBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Monter\ArtBundle\Entity\Gallery;
use Monter\ArtBundle\Form\GalleryType;

/**
 * Gallery controller.
 *
 * @Route("/admin/gallery")
 */
class AdminGalleryController extends Controller
{

    /**
     * Lists all Page entities.
     *
     * @Route("/{pageId}", name="admin_gallery_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($pageId)
    {
        $page = $this->getDoctrine()->getRepository('MonterArtBundle:Page')->find($pageId);
        if ( empty( $page ) ) {
            return $this->redirect( $this->generateUrl('admin_page') );
        }

        return array(
            'page' => $page,
        );
    }

    /**
     * Creates a new Gallery entity.
     *
     * @Route("/{pageId}", name="admin_gallery_create")
     * @Method("POST")
     * @Template("MonterArtBundle:AdminGallery:new.html.twig")
     */
    public function createAction(Request $request, $pageId)
    {
        $entity = new Gallery();
        $form = $this->createCreateForm($entity, $pageId);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->upload();
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_gallery_list', array('pageId' => $pageId)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'pageId' => $pageId,
        );
    }

    /**
    * Creates a form to create a Gallery entity.
    *
    * @param Gallery $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Gallery $entity, $pageId)
    {
        $form = $this->createForm(new GalleryType($pageId), $entity, array(
            'action' => $this->generateUrl('admin_gallery_create', array('pageId' => $pageId)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Vytvor', 'attr' => array('class' => 'btn btn-primary col-sm-offset-4')));

        return $form;
    }

    /**
     * Displays a form to create a new Gallery entity.
     *
     * @Route("/new/{pageId}", name="admin_gallery_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($pageId)
    {
        $entity = new Gallery();
        $form   = $this->createCreateForm($entity, $pageId);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'pageId' => $pageId,
        );
    }

    /**
     * Displays a form to edit an existing Gallery entity.
     *
     * @Route("/{id}/edit", name="admin_gallery_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MonterArtBundle:Gallery')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find gallery entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Gallery entity.
    *
    * @param Gallery $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Gallery $entity)
    {
        $form = $this->createForm(new GalleryType($entity->getPage()->getId()), $entity, array(
            'action' => $this->generateUrl('admin_gallery_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Uprav', 'attr' => array('class' => 'btn btn-primary col-sm-offset-4')));

        return $form;
    }
    /**
     * Edits an existing Gallery entity.
     *
     * @Route("/{id}", name="admin_gallery_update")
     * @Method("PUT")
     * @Template("MonterArtBundle:AdminGallery:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MonterArtBundle:Gallery')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gallery entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->upload();
            $em->flush();

            return $this->redirect($this->generateUrl('admin_gallery_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a Gallery entity.
     *
     * @Route("/delete/{id}", name="admin_gallery_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MonterArtBundle:Gallery')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gallery entity.');
        }
        $pageId = $entity->getPage()->getId();
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_gallery_list', array('pageId' => $pageId)));
    }
}
