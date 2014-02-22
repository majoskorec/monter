<?php

namespace Monter\ArtBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Monter\ArtBundle\Entity\Page;
use Monter\ArtBundle\Entity\Image;
use Monter\ArtBundle\Form\ImageType;

/**
 * Image controller.
 *
 * @Route("/admin/image")
 */
class AdminImageController extends Controller
{
    /**
     * Creates a new Image entity.
     *
     * @Route("/{pageId}/{type}", name="admin_image_create")
     * @Method("POST")
     * @Template("MonterArtBundle:AdminImage:new.html.twig")
     */
    public function createAction(Request $request, $pageId, $type)
    {
        $entity = new Image();
        $form = $this->createCreateForm($entity, $pageId, $type);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->upload();

            $em->persist($entity);

            $page = $em->find('MonterArtBundle:Page', $pageId);
            $this->setImageToPage($page, $entity, $type);

            $em->flush();

            return $this->redirect($this->generateUrl('admin_page_edit', array('id' => $pageId)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'pageId' => $pageId,
        );
    }

    /**
     * @param \Monter\ArtBundle\Entity\Page $page
     * @param \Monter\ArtBundle\Entity\Image $image
     * @param string $type
     */
    private function setImageToPage(Page $page, Image $image, $type)
    {
        switch ($type) {
            case Page::IMG_BACK:
                $page->setBackImg($image);
                break;
            case Page::IMG_BACK_HOVER:
                $page->setBackImgHover($image);
                break;
            case Page::IMG_BUTTON:
                $page->setButtonImg($image);
                break;
            case Page::IMG_BUTTON_HOVER:
                $page->setButtonImgHover($image);
                break;
            case Page::IMG_CONTENT:
                $page->setContent($image);
                break;
            case Page::IMG_DESCRIPTION:
                $page->setDescriptionImg($image);
                break;
            case Page::IMG_TITLE:
                $page->setTitleImg($image);
                break;
        }
        $this->getDoctrine()->getManager()->persist($page);
    }

    /**
    * Creates a form to create a Image entity.
    *
    * @param Image $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Image $entity, $pageId, $type)
    {
        $form = $this->createForm(new ImageType(), $entity, array(
            'action' => $this->generateUrl('admin_image_create', array('pageId' => $pageId, 'type' => $type)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Vytvor', 'attr' => array('class' => 'btn btn-primary col-sm-offset-4')));

        return $form;
    }

    /**
     * Displays a form to create a new Image entity.
     *
     * @Route("/new/{pageId}/{type}", name="admin_image_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($pageId, $type)
    {
        $entity = new Image();
        $form   = $this->createCreateForm($entity, $pageId, $type);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'pageId' => $pageId,
        );
    }

    /**
     * Displays a form to edit an existing Image entity.
     *
     * @Route("/{id}/edit/{pageId}", name="admin_image_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, $pageId)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MonterArtBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $editForm = $this->createEditForm($entity, $pageId);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'pageId'      => $pageId,
        );
    }

    /**
    * Creates a form to edit a Image entity.
    *
    * @param Image $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Image $entity, $pageId)
    {
        $form = $this->createForm(new ImageType(), $entity, array(
            'action' => $this->generateUrl('admin_image_update', array('id' => $entity->getId(), 'pageId' => $pageId)),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Uprav', 'attr' => array('class' => 'btn btn-primary col-sm-offset-4')));

        return $form;
    }
    /**
     * Edits an existing Image entity.
     *
     * @Route("/{id}/{pageId}", name="admin_image_update")
     * @Method("PUT")
     * @Template("MonterArtBundle:AdminImage:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $pageId)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MonterArtBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $editForm = $this->createEditForm($entity, $pageId);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
//
//            /* @var $image \Symfony\Component\HttpFoundation\File\UploadedFile */
//            $image = $editForm->get('file')->getData();
//            $image->move( $entity->getUploadRootDir(), $entity->getTempImgName() );
//
//            $content = \file_get_contents( $entity->getTempImgPath() );
//            $entity->setImage( \base64_encode( $content ) );
            $entity->upload();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_image_edit', array('id' => $id, 'pageId' => $pageId)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'pageId'      => $pageId,
        );
    }

    /**
     * Deletes a Image entity.
     *
     * @Route("/{id}/{pageId}", name="admin_image_delete")
     * @Method("GET")
     */
    public function deleteAction($id, $pageId)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MonterArtBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_page_edit', array('id' => $pageId)));
    }
}
