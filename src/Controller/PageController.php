<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Page;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{page}', name: 'page')]
final class PageController extends AbstractController
{
    public function __invoke(
        string $page,
        #[MapEntity(mapping: ['page' => 'urlKey'])]
        ?Page $pageEntity = null,
    ): Response {
        if ($pageEntity === null) {
            throw $this->createNotFoundException('missing page: ' . $page);
        }

        return $this->render('page/index.html.twig', [
            'page' => $pageEntity,
        ]);
    }
}
