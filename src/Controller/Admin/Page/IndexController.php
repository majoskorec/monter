<?php

declare(strict_types=1);

namespace App\Controller\Admin\Page;

use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/page', name: 'admin_page_index', methods: ['GET'])]
final class IndexController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager): Response
    {
        $entities = $entityManager->getRepository(Page::class)->findBy(['parentPage' => null]);

        return $this->render('admin/page/index.html.twig', ['entities' => $entities]);
    }
}
