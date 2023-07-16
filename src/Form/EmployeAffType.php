<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Employe;
use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmployeAffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('Nom_Prenom', TextType::class, [
                'disabled' => true,
            ])
            ->add('numTell', TextType::class, [
                'disabled' => true,
            ])
            ->add('numFax', TextType::class, [
                'disabled' => true,
            ])
            ->add('numcarte', TextType::class, [
                'disabled' => true,
            ])
            ->add('numcompte', TextType::class, [
                'disabled' => true,
            ])
            ->add('profession', TextType::class, [
                'disabled' => true,
            ])
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'dep',
                'disabled' => true,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Adresse e-mail',
                'choice_label' => 'email'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
