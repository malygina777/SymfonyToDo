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
{   // Cette classe décrit le formulaire utilisé pour créer ou modifier une tâche.
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   // On construit ici les différents champs du formulaire "Task".
        $builder
             // Champ "title" : titre de la tâche, simple champ texte.
            ->add('title')
            // Champ "dueAt" : date à laquelle l’utilisateur souhaite être alerté.
            // On utilise un DateType pour avoir un champ de type date (calendrier HTML5).
             ->add('dueAt', DateType::class, [   
                // 'single_text' permet d’afficher la date dans un seul champ, avec le calendrier HTML5, au lieu de trois champs séparés (jour / mois / année).
                'widget' => 'single_text',  
                // Le champ n’est pas obligatoire : l’utilisateur peut laisser la date vide.     
                'required' => false,  
                // Le texte qui sert d’étiquette pour le champ.           
                'label' => 'Date à laquelle vous souhaitez etre alerté',
             ])
             // Champ "status" : permet de choisir le statut de la tâche dans une liste.
            ->add('status', ChoiceType::class, [
                'label'   => 'Statut',
                // Liste des choix possibles dans le menu déroulant. La clé est le texte affiché à l’utilisateur, la valeur est stockée en base
                'choices' => [
                    'À faire'  => 'todo',
                    'En cours' => 'doing',
                    'Terminé'  => 'done',
                    'Urgent'   => 'urgent',
                ],
                // Texte affiché quand aucun statut n’est encore sélectionné
                'placeholder' => 'Sélectionnez un statut',
                // Attributs HTML du champ (ici, on ajoute une classe CSS Bootstrap)
                'attr' => ['class' => 'form-select'],
            ])
            // Champ "description" : texte optionnel pour décrire la tâche
            ->add('description', TextareaType::class, [
                // Le champ peut être laissé vide
                'required' => false,
                // Le texte qui sert d’étiquette pour le champ.
                'label' => 'description',
                // Attributs HTML supplémentaires pour le <textarea>
                'attr' => [
                    // Nombre de lignes visibles par défaut
                    'rows' => 3,
                    // Texte d’aide affiché à l’intérieur du champ quand il est vide.
                    'placeholder' => 'Décrivez la tâche…',
                    // Classe CSS pour appliquer le style Bootstrap
                    'class' => 'form-control',
                ]
                ]);
    }
    // Configuration des options du formulaire.
    // Ici, on indique que ce formulaire travaille avec l’entité Task.
    // Symfony remplira automatiquement un objet Task avec les données du formulaire
    public function configureOptions(OptionsResolver $resolver): void // Le type void indique simplement que la fonction ne renvoie rien. 
                                                                      // Elle configure les options du formulaire, mais elle ne retourne pas de valeur.
  {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}






