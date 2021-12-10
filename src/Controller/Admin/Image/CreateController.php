<?php

declare(strict_types=1);

namespace App\Controller\Admin\Image;

use App\Entity\Image;
use App\Entity\Page;
use App\Form\Type\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateController extends AbstractController
{
    #[Route(path: '/admin/image/create/{pageId}/{type}', name: 'admin_image_create', methods: ['GET', 'POST'])]
    #[ParamConverter('page', class: Page::class, options: ['mapping' => ['pageId' => 'id']])]
    public function __invoke(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        Page $page,
        string $type,
    ): Response {
        $entity = new Image();
        $form = $formFactory->create(ImageType::class, $entity, ['create' => true]);
        $form->add('submit', SubmitType::class, [
            'label' => 'Vytvor',
            'attr' => ['class' => 'btn btn-primary col-sm-offset-4'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $page->setImage($entity, $type);
            $entityManager->flush();

            return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
        }

        return $this->render('admin/image/create.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
            'pageId' => $page->getId(),
        ]);
    }
}
