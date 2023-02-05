<?php

declare(strict_types=1);

namespace App\Controller\Admin\Page;

use App\Entity\Page;
use App\Form\Type\PageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/page/create/{parentId}', name: 'admin_page_create', methods: ['GET', 'POST'])]
final class CreateController extends AbstractController
{
    public function __invoke(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        ?int $parentId = null
    ): Response {
        $entity = new Page();
        $form = $formFactory->create(PageType::class, $entity, ['parentPageId' => $parentId]);
        $form->add('submit', SubmitType::class, [
            'label' => 'Vytvor',
            'attr' => ['class' => 'btn btn-primary col-sm-offset-4'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute('admin_page_edit', ['id' => $entity->getId()]);
        }

        return $this->render('admin/page/create.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }
}
