<?php

declare(strict_types=1);

namespace App\Controller\Admin\Image;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DeleteController extends AbstractController
{
    #[Route(path: '/admin/image/{id}/delete/{pageId}', name: 'admin_image_delete', methods: ['GET'])]
    public function __invoke(EntityManagerInterface $entityManager, Image $image, int $pageId): Response
    {
        $entityManager->remove($image);
        $entityManager->flush();

        return $this->redirectToRoute('admin_page_edit', ['id' => $pageId]);
    }
}
