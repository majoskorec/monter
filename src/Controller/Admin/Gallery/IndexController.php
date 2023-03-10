<?php

declare(strict_types=1);

namespace App\Controller\Admin\Gallery;

use App\Entity\Page;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/gallery/{pageId}', name: 'admin_gallery_index', methods: ['GET'])]
final class IndexController extends AbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['pageId' => 'id'])]
        Page $page,
    ): Response {
        return $this->render('admin/gallery/index.html.twig', [
            'page' => $page,
            'pageId' => $page->getId(),
        ]);
    }
}
