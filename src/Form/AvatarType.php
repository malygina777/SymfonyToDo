<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
         $builder->add('avatarFile', FileType::class, [
           
            'mapped' => false,            // <- важно: это не поле сущности
            'required' => true,
            'constraints' => [
                new Image([
                     'maxSize' => '10M',
                     'mimeTypes' => ['image/jpeg','image/png','image/webp'], // без svg
                     'maxWidth'  => 8000,
                     'maxHeight' => 8000,
                    
                ]),
            ],
            'attr' => ['accept' => 'image/*'], // просто удобство для выбора
        ]);
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'avatar_upload',
        ]);
    }
}
