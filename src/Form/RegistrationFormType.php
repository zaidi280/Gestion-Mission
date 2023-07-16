<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ adresse e-mail ne peut pas être vide.',
                    ]),
                    new Email([
                        'message' => 'L\'adresse e-mail "{{ value }}" n\'est pas valide(le mail doit être sous forme"exemple@exemple.exemple ',
                        'mode' => 'strict',
                    ]),
                ],
            ])

            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Employe' => 'ROLE_EMPLOYE',
                    'Chef de parc' => 'ROLE_CHEF_DE_PARC',
                    'Secritaire Generale' => 'ROLE_SCGEN',
                    'Personnel' => 'ROLE_PERSONNEL',
                    'Service Financier' => 'ROLE_FINANCIER'
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ rôle ne peut pas être vide.',
                    ]),
                    new Count([
                        'max' => 1,
                        'maxMessage' => 'Vous ne pouvez sélectionner qu\'un seul rôle.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
