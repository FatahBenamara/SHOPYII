<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints; 

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')

            ->add('role', ChoiceType::class, array(
                'choices' => array(
                    'Membre' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN'
                ),
                //'required'  => true,
               // "multiple" => true,
                //"label" => "Rôles"

            ))



            ->add('password', Type\PasswordType::class,["required" => false, "mapped"=> false,
                                                        "constraints"=>[new Constraints\Length(["min"=>6,
                                                        "minMessage"=>"le mot de passe doit contenir au moins 6 caractéres"  ])]            
            
                                                        ])


            ->add('pseudo')
            ->add('nom')
            ->add('civilite')
            ->add('ville')
            ->add('code_postal')
            ->add('adresse')
            ->add('prenom');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
