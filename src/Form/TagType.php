<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', null, [
                'label' => "Nom du tag",
                'attr' =>
                    [
                        'placeholder' => "Nom du tag",
                        'class' => 'form-control mb-3'
                    ],
            ])
            ->add('description', null, [
                'attr' =>
                    [
                        'class' => 'form-control mb-3 tinymce ',
                        'placeholder' => 'Description'

                    ],
                'required' => false
            ])
            ->add('illustration', FileType::class, [
                'label' => "Image d'illustration",
                'mapped' => false,
                'required' => false,
                'attr' =>
                    [
                        'class' => 'form-control mb-3',
                    ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Veuillez choisir un fichier de type image',
                    ])
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
