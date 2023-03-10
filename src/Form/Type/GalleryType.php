<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Gallery;
use App\Entity\Page;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @template-extends AbstractType<Gallery>
 */
final class GalleryType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var int $pageId */
        $pageId = $options['pageId'];
        /** @var bool $create */
        $create = $options['create'];
        $builder->add('urlKey', TextType::class, [
            'label' => 'Url klúč',
            'required' => true,
        ]);
        $builder->add('title', TextType::class, [
            'label' => 'Titulka',
            'required' => true,
        ]);
        $builder->add('page', EntityType::class, [
            'label' => 'Stránka',
            'class' => Page::class,
            'query_builder' => function (EntityRepository $er) use ($pageId) {
                return $er->createQueryBuilder('p')
                    ->where('p.id = :pageId')
                    ->setParameter('pageId', $pageId);
            },
            'attr' => [
                'readonly' => true,
            ],
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
        $resolver->setRequired('pageId');
        $resolver->setRequired('create');
        $resolver->setAllowedTypes('pageId', ['int']);
        $resolver->setAllowedTypes('create', ['bool']);
        $resolver->setDefaults([
            'data_class' => Gallery::class,
        ]);
    }
}
