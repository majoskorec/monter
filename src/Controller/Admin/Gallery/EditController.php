<?php

declare(strict_types=1);

namespace App\Controller\Admin\Gallery;

use App\Entity\Gallery;
use App\Form\Type\GalleryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/gallery/{id}/edit', name: 'admin_gallery_edit', methods: ['GET', 'PUT'])]
final class EditController extends AbstractController
{
    public function __invoke(
        EntityManagerInterface $entityManager,
        Request $request,
        FormFactoryInterface $formFactory,
        Gallery $gallery
    ): Response {
        $form = $formFactory->create(GalleryType::class, $gallery, [
            'pageId' => $gallery->getPage()->getId(),
            'method' => 'PUT',
            'create' => false,
        ]);
        $form->add('submit', SubmitType::class, [
            'label' => 'Uprav',
            'attr' => ['class' => 'btn btn-primary col-sm-offset-4'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gallery->upload();
            $entityManager->flush();

            return $this->redirectToRoute('admin_gallery_edit', ['id' => $gallery->getId()]);
        }

        return $this->render('admin/gallery/edit.html.twig', [
            'entity' => $gallery,
            'form' => $form,
            'pageId' => $gallery->getPage()->getId(),
        ]);
    }
}
