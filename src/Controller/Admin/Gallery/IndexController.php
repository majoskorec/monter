<?php

declare(strict_types=1);

namespace App\Controller\Admin\Gallery;

use App\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    #[Route(path: '/admin/gallery/{pageId}', name: 'admin_gallery_index', methods: ['GET'])]
    #[ParamConverter('page', class: Page::class, options: ['mapping' => ['pageId' => 'id']])]
    public function __invoke(Page $page): Response
    {
        return $this->render('admin/gallery/index.html.twig', ['page' => $page]);
    }
}
