<?php

namespace Monter\ArtBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class GalleryType extends \Monter\ArtBundle\Form\AbstractType
{
    private $pageId;

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'Gallery';
    }

    /**
     * @param int $pageId
     */
    public function __construct($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pageId = $this->pageId;
        $builder
            ->add('urlKey', null, $this->getAttr('Url klúč'))
            ->add('title', null, $this->getAttr('Titulka'))
            ->add('page', null, $this->getAttr('Stránka', array(
                'class' => 'MonterArtBundle:Page',
                'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($pageId) {
                    return $er->createQueryBuilder('p')
                            ->where('p.id = :pageId')
                            ->setParameter('pageId', $pageId);
                },
                'read_only' => true,
                'required' => true,
            )))
            ->add('file', 'file', $this->getAttr('Obrázok', array('required' => true)))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'monter_artbundle_gallery';
    }
}
