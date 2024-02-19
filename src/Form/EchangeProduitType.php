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
                'choice_label' => 'titreProduit', 
                'placeholder' => 'Select your product Product', 
                'required' => true, 
    
            ])
            ->add('produitOut', EntityType::class, [
                'class' => 'App\Entity\Produit', 
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
