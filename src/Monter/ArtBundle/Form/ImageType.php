<?php

namespace Monter\ArtBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class ImageType extends \Monter\ArtBundle\Form\AbstractType
{
    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'Image';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, $this->getAttr('Názov'))
            ->add('file', 'file', $this->getAttr('Obrázok', array('required' => true)))
        ;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'monter_artbundle_image';
    }
}
