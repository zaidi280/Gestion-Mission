<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Categorie;
use App\Entity\Profession;
use App\Entity\Departement;
use App\Entity\Destination;
use App\Entity\DemandeMission;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ModifMissType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'type',
                // 'multiple' => false,
                //'expanded' => true,
            ])
            ->add('destination', EntityType::class, [
                'class' => Destination::class,
                'choice_label' => 'region',
                // 'multiple' => false,
                //'expanded' => true,
                'label' => 'Destination',
            ])
            ->add('Sujet', TextareaType::class, [
                'label' => 'Description',

            ])
            ->add('DateDebut', null, [
                'widget' => 'single_text',
                'disabled' => true,
            ])
            ->add('DateFin', null, [
                'widget' => 'single_text',
                'disabled' => true,
            ])
            ->add('Employes', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'Nom_Prenom',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Accompagnant',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.supprimer = :supprimer')
                        ->setParameter('supprimer', false);
                },
            ])

            ->add('materiel', TextareaType::class, [
                'label' => 'Equipement et matériel à transporter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeMission::class,
        ]);
    }
}
