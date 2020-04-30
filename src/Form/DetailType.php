<?php

namespace App\Form;

use App\Entity\Detail;
use App\Entity\Commande;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Constraints;

class DetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite')
            ->add('prix', MoneyType::class, ["constraints" => [
                new Constraints\Range(
                    [
                        'min' => 10,
                        'max' => 1500,
                        'minMessage' => 'Le prix doit être de 10€ au minimum',
                        'maxMessage' => 'Le prix doit être de  {{ limit }}€ au maximum',
                    ]
                )

            ]])

            ->add('commande', EntityType::class, ["class" => Commande::class, "choice_label" => "etat"])
            ->add('produit', EntityType::class, ["class" => Produit::class, "choice_label" => "couleur"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Detail::class,
        ]);
    }
}
