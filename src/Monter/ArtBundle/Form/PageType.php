<?php

namespace Monter\ArtBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class PageType extends \Monter\ArtBundle\Form\AbstractType
{
    private $parentPageId;

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'Page';
    }

    /**
     * @param int $parentPageId
     */
    public function __construct($parentPageId = null)
    {
        $this->parentPageId = $parentPageId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $parentPageId = $this->parentPageId;
        $builder
            ->add('urlKey', null, $this->getAttr('Url klúč'))
            ->add('title', null, $this->getAttr('Titulka'))
            ->add('row', null, $this->getAttr('Riadok'))
        ;
        if ( !empty($parentPageId) ) {
            $builder->add('parentPage', null, $this->getAttr('Nadriadená stránka', array(
                'class' => 'MonterArtBundle:Page',
                'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($parentPageId) {
                    return $er->createQueryBuilder('p')
                            ->where('p.id = :parentPageId')
                            ->setParameter('parentPageId', $parentPageId);
                },
                'read_only' => true,
                'required' => true,
            )));
        }
//            ->add('description')
//            ->add('content')
//            ->add('buttonImg')
//            ->add('buttonImgHover')
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'monter_artbundle_page';
    }
}
