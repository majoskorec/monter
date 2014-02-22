<?php

namespace Monter\ArtBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractType extends \Symfony\Component\Form\AbstractType
{
    const ENTITY_DIR = 'Monter\ArtBundle\Entity\\';

    /**
     * @return string
     */
    abstract protected function getEntityName();

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => self::ENTITY_DIR . $this->getEntityName(),
        ));
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param string $label
     * @param array $attr
     * @return array
     */
    protected function getAttr($label, array $attr = array())
    {
        $preDefAttr = array(
            'label' => $label,
            'label_attr' => array(
                'class' => 'col-sm-2 control-label text-right'
            ),
            'attr' => array(
                'class' => 'form-control'
            ),
        );
        
        return array_merge_recursive($preDefAttr, $attr);
    }
}