<?php

declare(strict_types=1);

namespace App\Controller\Admin\Gallery;

use App\Entity\Gallery;
use App\Entity\Page;
use App\Form\Type\GalleryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/gallery/create/{pageId}', name: 'admin_gallery_create', methods: ['GET', 'POST'])]
final class CreateController extends AbstractController
{
    public function __invoke(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        #[MapEntity(mapping: ['pageId' => 'id'])]
        Page $page,
    ): Response {
        $entity = new Gallery();
        $form = $formFactory->create(GalleryType::class, $entity, ['pageId' => $page->getId(), 'create' => true]);
        $form->add('submit', SubmitType::class, [
            'label' => 'Vytvor',
            'attr' => ['class' => 'btn btn-primary col-sm-offset-4'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity->upload();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute('admin_gallery_index', ['pageId' => $page->getId()]);
        }

        return $this->render('admin/gallery/create.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
            'pageId' => $page->getId(),
        ]);
    }
}
