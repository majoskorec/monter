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
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/page/{id}/edit', name: 'admin_page_edit', methods: ['GET', 'PUT'])]
final class EditController extends AbstractController
{
    public function __invoke(
        Page $page,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $form = $formFactory->create(PageType::class, $page, [
            'method' => 'PUT',
            'parentPageId' => $page->getParentPage()?->getId(),
        ]);
        $form->add('submit', SubmitType::class, [
            'label' => 'Uprav',
            'attr' => ['class' => 'btn btn-primary col-sm-offset-4'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
        }

        return $this->render('admin/page/edit.html.twig', [
            'entity' => $page,
            'form' => $form,
            'pageId' => $page->getId(),
        ]);
    }
}
