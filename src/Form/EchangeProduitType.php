<?php

namespace App\Form;

use App\Entity\EchangeProduit;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EchangeProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_echange')
            ->add('valide')
            ->add('produitIn', EntityType::class, [
                'class' => 'App\Entity\Produit',  
                'choice_label' => function ($produit) {
                    return $produit->getTitreProduit() . ' - ' . $produit->getDescriptionProduit() . ' - ' . $produit->getPrix() . ' \n Seller Info : ' . $produit->getUtilisateur()->getNom() . 'Email'. $produit->getUtilisateur()->getEmail() . 'Phone Number' .$produit->getUtilisateur()->getTelephone();
                },
                'placeholder' => 'Select your product Product', 
                'required' => true, 
                'disabled' => true, // Set the produitIn field as read-only
            ])
            ->add('produitOut', EntityType::class, [
                'class' => Produit::class,
                'choices' => $options['userProducts'],
                'choice_label' => 'titreProduit', 
                'placeholder' => 'Select the exchanged Product',
                'required' => true, 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EchangeProduit::class,
        ]);
    }
}
