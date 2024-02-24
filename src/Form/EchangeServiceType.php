<?php

namespace App\Form;

use App\Entity\EchangeService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EchangeServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_echange')
            ->add('valide')
            ->add('serviceIn', EntityType::class, [
                'class' => 'App\Entity\Service',  
                'choice_label' => 'titreService', 
                'placeholder' => 'Select your product Product', 
                'required' => true, 
    
            ])
            ->add('serviceOut', EntityType::class, [
                'class' => 'App\Entity\Service', 
                'choice_label' => 'titreService', 
                'placeholder' => 'Select the exchanged Service',
                'required' => true, 
    
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EchangeService::class,
        ]);
    }
}
