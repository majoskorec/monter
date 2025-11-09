<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Page;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{page}/gallery', name: 'page-gallery')]
#[Route('/{page}/gallery/{gallery}', name: 'gallery')]
final class GalleryController extends AbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['page' => 'urlKey'])]
        Page $pageEntity,
        ?string $gallery = null,
    ): Response {
        $galleryEntity = $this->getGalleryEntity($pageEntity, $gallery);
        $params = [
            'gallery' => $galleryEntity,
            'nextGallery' => null,
            'prevGallery' => null,
        ];
        $params = $this->addPrevAndNextGalleryToParams($params, $galleryEntity);

        return $this->render('gallery/index.html.twig', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    private function addPrevAndNextGalleryToParams(array $params, Gallery $galleryEntity): array
    {
        $findCurrent = false;
        foreach ($galleryEntity->getPage()->getGallery() as $item) {
            if ($findCurrent === true) {
                $params['nextGallery'] = $item->getUrlKey();

                return $params;
            }
            if ($item->getId() === $galleryEntity->getId()) {
                $findCurrent = true;

                continue;
            }

            $params['prevGallery'] = $item->getUrlKey();
        }

        return $params;
    }

    private function getGalleryEntity(Page $pageEntity, ?string $gallery): Gallery
    {
        $collection = $pageEntity->getGallery();
        if ($gallery !== null) {
            $collection = $collection->filter(static fn (Gallery $entity) => $entity->getUrlKey() === $gallery);
        }

        $entity = $collection->first();
        if ($entity === false) {
            throw $this->createNotFoundException('missing gallery' . ($gallery ?? 'n/a'));
        }

        return $entity;
    }
}
