<?php

declare(strict_types=1);

namespace App\Controller\Admin\Page;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/page/{id}/delete', name: 'admin_page_delete', methods: ['GET'])]
final class DeleteController extends AbstractController
{
    public function __invoke(Page $page, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($page);
        $entityManager->flush();

        return $this->redirectToRoute('admin_page_index');
    }
}
