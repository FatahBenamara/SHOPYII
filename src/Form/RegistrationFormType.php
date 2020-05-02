<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo',TextType::class, [ 
                                                "help" => "Tapez votre pseudo",
                                                "data" => "",
                                                "constraints" => [ 
                             new NotBlank([
                                            "message" => "Vous avez oublié de remplir ce champ"
                                        ]),
                             new Length([
                                            "min" => 3, "max" => 20,
                                            "minMessage" => "Le pseudo doit contenir au moins 3 caractères",
                                            "maxMessage" => "Le pseudo ne doit pas dépasser 20 caractères"
                                            
                                        ])


                                          
                                    ]])


            ->add('nom', TextType::class, [ 
                                            "help" => "Tapez votre nom",
                                            "data" => "",
                                            "constraints" => [ 
                             new NotBlank(["message" => "Vous avez oublié de remplir ce champ"]),
                             new Length(["min" => 1, "max" => 20,
                                         "minMessage" => "Le nom doit contenir au moins 1 caractères",
                                         "maxMessage" => "Le nom ne doit pas dépasser 20 caractères"])
                             ]])

            ->add('prenom', TextType::class, [ "help" => "Tapez votre prénom",
                                        "data" => "",
                                        "constraints" => [ 
                             new NotBlank(["message" => "Vous avez oublié de remplir ce champ"]),
                             new Length(["min" => 1, "max" => 20,
                                         "minMessage" => "Le prénom doit contenir au moins 1 caractères",
                                         "maxMessage" => "Le prénom ne doit pas dépasser 20 caractères"])
                             ]])

            ->add('email', EmailType::class, [  "help" => "Tapez votre email",
                                                "data" => "",
                                                "constraints" => [ 
                             new NotBlank(["message" => "Vous avez oublié de remplir ce champ"]),
                             new Length(["min" => 3, "max" => 50,
                                         "minMessage" => "L'email' doit contenir au moins 3 caractères",
                                         "maxMessage" => "L'email' ne doit pas dépasser 50 caractères"])
                             ]])
       
            ->add('plainPassword',RepeatedType::class, array(
                'type' => PasswordType::class,
                'mapped' => false,

                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'first_options'  => array('label' => 'Mot de passe',
                    
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir un mot de passe : ',
                        ]),
               

                        new Regex(array(
                            'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\-!?$#@\_])^[\w\d\-!?$#@]{8,20}$/',
                            'message' => 'Veuillez saisir un mot de passe composé de 8 caractères dont une MAJ, une minuscule, un chiffre et une caractère spécial (-!?$#@)'
                        )),

                    ],
                    ),
                'second_options' => array('label' => 'Confirmation du mot de passe'),
                
            ))

            ->add('civilite', ChoiceType::class,[
                'label' => 'Civilité',
                "choices" => [
                    "Homme" => "h",
                    "Femme" => "f",
                    "Autre" => "a"
                ]
            ])
            ->add('ville', TextType::class)
            ->add('code_postal', TextType::class, [ 
                                    "label" => "Code Postal",
                                    "constraints" => [ new Length([
                                    "min" => 5, "max" => 5,
                                    "exactMessage" => "Le code postal doit comporter 5 chiffres exactement"
                                ]),
                               new Regex([ "pattern" => "/[0-9]{5}/", 
                                           "message" => "Le code postal doit comporter 5 chiffres exactement"
                                         ]) 
                             ] ])
            ->add('adresse', TextType::class)
            
       
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez valider les Conditions Générales d\'Utilisation',
                    ]),
                ],
                'label' => "J'accepte les conditions générales d'utilisation"
            ])

            ->add('Enregistrer', SubmitType::class, [ "attr" => [ "class" => "btn btn-primary" ] ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
