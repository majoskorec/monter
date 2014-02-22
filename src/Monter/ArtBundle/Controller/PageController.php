<?php

namespace Monter\ArtBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PageController extends Controller
{
    /**
     * @Route("/{page}", name="page")
     * @Template()
     */
    public function indexAction($page)
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
}
