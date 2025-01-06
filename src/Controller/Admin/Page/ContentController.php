<?php

declare(strict_types=1);

namespace App\Controller\Admin\Page;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/page/{id}/content', name: 'admin_get_page_content', methods: ['GET'])]
final class ContentController extends AbstractController
{
    public function __invoke(Page $page): Response
    {
        return $this->render('admin/page/content.html.twig', ['page' => $page]);
    }
}
