<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
             ->add('dueAt', DateType::class, [   // <-- новое поле
                'widget' => 'single_text',       // календарик HTML5
                'required' => false,             // можно оставить пустым
                'label' => 'Date à laquelle vous souhaitez etre alerté',
             ])
            ->add('status', ChoiceType::class, [
                'label'   => 'Statut',
                'choices' => [
                    'À faire'  => 'todo',
                    'En cours' => 'doing',
                    'Terminé'  => 'done',
                    'Urgent'   => 'urgent',
                ],
                'placeholder' => 'Sélectionnez un statut',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'description',
                'attr' => [
                    'rows' => 3,
                    'placeholder' => 'Décrivez la tâche…',
                    'class' => 'form-control',
                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
