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
}
