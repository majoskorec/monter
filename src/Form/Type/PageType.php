<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Page;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template-extends AbstractType<Page>
 */
final class PageType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var int|null $parentPageId */
        $parentPageId = $options['parentPageId'];
        $builder->add('urlKey', TextType::class, [
            'label' => 'Url klúč',
        ]);
        $builder->add('title', TextType::class, [
            'label' => 'Titulka',
        ]);
        $builder->add('row', NumberType::class, [
            'label' => 'Riadok',
        ]);
        if ($parentPageId !== null) {
            $builder->add('parentPage', EntityType::class, [
                'label' => 'Nadriadená stránka',
                'class' => Page::class,
                'query_builder' => function (EntityRepository $er) use ($parentPageId) {
                    return $er->createQueryBuilder('p')
                        ->where('p.id = :parentPageId')
                        ->setParameter('parentPageId', $parentPageId);
                },
                'required' => true,
                'attr' => [
                    'readonly' => true,
                ],
            ]);
        }
        $builder->add('externalLink', UrlType::class, [
            'required' => false,
            'default_protocol' => null,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('parentPageId');
        $resolver->setAllowedTypes('parentPageId', ['null', 'int']);
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
