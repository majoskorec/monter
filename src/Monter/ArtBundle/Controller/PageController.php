<?php

namespace Monter\ArtBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PageController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('page', array('page' => 'index')));
    }

    /**
     * @Route("/{page}", name="page")
     * @Template()
     */
    public function pageAction($page)
    {
        /* @var $pageEntity \Monter\ArtBundle\Entity\Page */
        $pageEntity = $this->getDoctrine()->getRepository('MonterArtBundle:Page')->findOneBy(array('urlKey' => $page));
        if ( empty( $pageEntity) ) {
            throw new \Exception(__CLASS__ . '::' . __FUNCTION__ . ' - missing page: ' . $page);
        }

        return array(
            'page' => $pageEntity,
        );
    }

    /**
     * @Route("/{page}/galeria", name="page-gallery")
     * @Route("/{page}/{gallery}", name="gallery")
     * @Template()
     */
    public function galleryAction($page, $gallery = null)
    {
        /* @var $galleryEntity \Monter\ArtBundle\Entity\Gallery */
        if ( empty( $gallery ) ) {
            $galleryEntity = $this->getDoctrine()
                    ->getRepository('MonterArtBundle:Gallery')
                    ->createQueryBuilder('g')
                    ->innerJoin('g.page', 'p')
                    ->where('p.urlKey = :page')
                    ->setParameters(array('page' => $page))
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();
        } else {
            $galleryEntity = $this->getDoctrine()
                    ->getRepository('MonterArtBundle:Gallery')
                    ->createQueryBuilder('g')
                    ->innerJoin('g.page', 'p')
                    ->where('p.urlKey = :page and g.urlKey = :gallery')
                    ->setParameters(array('page' => $page, 'gallery' => $gallery))
                    ->getQuery()
                    ->getOneOrNullResult();
        }
        if ( empty( $galleryEntity ) ) {
            throw new \Exception(__CLASS__ . '::' . __FUNCTION__ . ' - missing gallery ' . $page . '/' . $gallery);
        }

        $nextGallery = null;
        $prevGallery = null;
        $find = false;
        foreach ( $galleryEntity->getPage()->getGallery() as $item ) {
            if ( $find && is_null( $nextGallery ) ) {
                $nextGallery = $item->getUrlKey();
                break;
            }
            if ( $item->getId() == $galleryEntity->getId() ) {
                $find = true;
            }
            if ( !$find ) {
                $prevGallery = $item->getUrlKey();
            }
        }

        return array(
            'gallery' => $galleryEntity,
            'nextGallery' => $nextGallery,
            'prevGallery' => $prevGallery,
        );
    }
}
