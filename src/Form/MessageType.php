<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\Topic;
use App\Repository\TopicRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            // ->add('topic', EntityType::class, [
            //     'choice_label' => 'name',
            //     'class' => Topic::class,
            //     //permet de restreindre les resultats d'une liste
            //     'query_builder' => function (TopicRepository $topicRepository) {
            //         return $topicRepository->findByNameContainsBuilder("tre");
            //     }
            // ])
            ->add('topic', TopicType::class,
                ['help' => '<i class="fas fa-question-circle" data-toggle="tooltip" title="Message HELP"></i>',
                    'help_html' => true
                ]);
        //->add('topic')
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
