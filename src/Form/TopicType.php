<?php

namespace App\Form;

use App\Entity\Topic;
use App\Service\SlugService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use function Sodium\add;

class TopicType extends AbstractType
{
    private SlugService $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $this->slugService->slugify();
        $builder
            ->add('name', null, [
                'label' => 'Nom :',
                'attr' =>
                    [
                        'class' => 'form-control mb-3 ',
                        'placeholder' => 'Nom du topic'
                    ]
            ])
            ->add('description', null, [
                'label' => 'Description :',
                'attr' =>
                    [
                        'class' => 'form-control mb-3 tinymce ',
                        'placeholder' => 'Description'

                    ],
                'required' => false
            ])
            ->add('tags', null, [
                'label' => false,
                'choice_label' => 'text',
                'by_reference' => false,
                'attr' =>
                    [
                        'class' => 'form-control mb-3',
                    ]
            ]);


        /*
                    //formulaires imbriqué de création de tags
                    ->add('tags', CollectionType::class, [
                        'entry_type' => TagType::class,
                        'prototype' => true,
                        'allow_add' => true,
                        'label' => false,
                        'by_reference' => false,
                        //'constraints' => [new Count(['min' => 1])],
                        'attr' =>
                            [
                                'class' => 'mb-3',
                            ]
                    ]);
        */
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
