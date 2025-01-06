<?php

declare(strict_types=1);

namespace App\Controller\Admin\Image;

use App\Entity\Image;
use App\Entity\Page;
use App\Form\Type\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/image/{id}/edit/{pageId}', name: 'admin_image_edit', methods: ['GET', 'PUT'])]
final class EditController extends AbstractController
{
    public function __invoke(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        Image $image,
        #[MapEntity(mapping: ['pageId' => 'id'])]
        Page $page,
    ): Response {
        $form = $formFactory->create(ImageType::class, $image, ['method' => 'PUT', 'create' => false]);
        $form->add('submit', SubmitType::class, [
            'label' => 'Uprav',
            'attr' => ['class' => 'btn btn-primary col-sm-offset-4'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_image_edit', [
                'id' => $image->getId(),
                'pageId' => $page->getId(),
            ]);
        }

        return $this->render('admin/image/edit.html.twig', [
            'entity' => $image,
            'form' => $form,
            'pageId' => $page->getId(),
        ]);
    }
}
