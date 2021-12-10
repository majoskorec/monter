<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class PageController extends AbstractController
{
    #[Route('/{page}', name: 'page')]
    #[ParamConverter('pageEntity', class: Page::class, options: ['mapping' => ['page' => 'urlKey']])]
    public function __invoke(string $page, ?Page $pageEntity = null): Response
    {
        if ($pageEntity === null) {
            throw new NotFoundHttpException('missing page: ' . $page);
        }

        return $this->render('page/index.html.twig', [
            'page' => $pageEntity,
        ]);
    }
}
