<?php

namespace App\Form;



use App\Entity\AppUser;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir votre e-mail.']),
                    new Assert\Email(['message' => 'Adresse e-mail invalide.']),
                    new Assert\Length([
                        'max' => 180,
                        'maxMessage' => "L\'e-mail ne peut pas dépasser {{limit}} caractères.",
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir votre mot de passe.']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => "Le mot de passe doit contenir au moins {{limit}} caractères.",
                        'max' => 4096,
                        
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppUser::class,
             // Включаем защиту от CSRF-атак
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'user_registration',
        ]);
    }
}
