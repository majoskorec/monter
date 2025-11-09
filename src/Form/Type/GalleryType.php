<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Gallery;
use App\Entity\Page;
use Doctrine\ORM\EntityRepository;
use Override;
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
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $pageId = $options['pageId'];
        assert(is_int($pageId));
        $create = $options['create'];
        assert(is_bool($create));
        $builder->add('urlKey', TextType::class, [
            'label' => 'Url klúč',
            'required' => true,
        ]);
        $builder->add('title', TextType::class, [
            'label' => 'Titulka',
            'required' => true,
        ]);
        $builder->add('page', EntityType::class, [
            'attr' => [
                'readonly' => true,
            ],
            'class' => Page::class,
            'label' => 'Stránka',
            'query_builder' => static function (EntityRepository $er) use ($pageId) {
                return $er->createQueryBuilder('p')
                    ->where('p.id = :pageId')
                    ->setParameter('pageId', $pageId);
            },
            'required' => true,
        ]);
        $constrains = $create ? [new NotBlank()] : [];
        $builder->add('file', FileType::class, [
            'constraints' => $constrains,
            'label' => 'Obrázok',
            'required' => true,
        ]);
    }

    #[Override]
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
