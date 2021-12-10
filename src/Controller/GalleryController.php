<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class GalleryController extends AbstractController
{
    #[Route('/{page}/gallery', name: 'page-gallery')]
    #[Route('/{page}/gallery/{gallery}', name: 'gallery')]
    #[ParamConverter('pageEntity', class: Page::class, options: ['mapping' => ['page' => 'urlKey']])]
    public function __invoke(Page $pageEntity, ?string $gallery = null): Response
    {
        $galleryEntity = $this->getGalleryEntity($pageEntity, $gallery);
        /** @var string|null $nextGallery */
        $nextGallery = null;
        /** @var string|null $prevGallery */
        $prevGallery = null;
        $find = false;
        foreach ($galleryEntity->getPage()->getGallery() as $item) {
            if ($find === true && $nextGallery === null) {
                $nextGallery = $item->getUrlKey();
                break;
            }
            if ($item->getId() === $galleryEntity->getId()) {
                $find = true;
            }
            if (!$find) {
                $prevGallery = $item->getUrlKey();
            }
        }

        return $this->render('gallery/index.html.twig', [
            'gallery' => $galleryEntity,
            'nextGallery' => $nextGallery,
            'prevGallery' => $prevGallery,
        ]);
    }

    private function getGalleryEntity(Page $pageEntity, ?string $gallery): Gallery
    {
        $collection = $pageEntity->getGallery();
        if ($gallery !== null) {
            $collection = $collection->filter(fn (Gallery $entity) => $entity->getUrlKey() === $gallery);
        }

        $entity = $collection->first();
        if ($entity === false) {
            throw new NotFoundHttpException('');
        }

        return $entity;
    }
}
