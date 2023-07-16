<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_Prenom')
            ->add('NumTell')
            ->add('NumFax')
            ->add('numcarte')
            ->add('numcompte')
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'dep',

            ])
            ->add('profession');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
