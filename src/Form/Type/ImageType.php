<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @template-extends AbstractType<Image>
 */
final class ImageType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var bool $create */
        $create = $options['create'];
        $builder->add('title', TextType::class, [
            'label' => 'Názov',
            'required' => true,
        ]);
        $constrains = $create ? [new NotBlank()] : [];
        $builder->add('file', FileType::class, [
            'label' => 'Obrázok',
            'required' => true,
            'constraints' => $constrains,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('create');
        $resolver->setAllowedTypes('create', ['bool']);
        $resolver->setDefaults([
            'data_class' => Image::class
        ]);
    }
}
