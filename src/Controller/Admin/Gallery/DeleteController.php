<?php

declare(strict_types=1);

namespace App\Controller\Admin\Gallery;

use App\Entity\Gallery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/gallery/{id}/delete', name: 'admin_gallery_delete', methods: ['GET'])]
final class DeleteController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager, Gallery $entity): Response
    {
        $pageId = $entity->getPage()->getId();
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute('admin_gallery_index', ['pageId' => $pageId]);
    }
}
