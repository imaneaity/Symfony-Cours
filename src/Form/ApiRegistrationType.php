<?php

namespace App\Form;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ApiRegistrationType extends RegistrationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        parent::buildForm($builder, $options);


        $builder
            
            ->remove('password')
            ->add('password', PasswordType::clas)
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
       prent::configureOptions($resolver);
       
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string 
    {
        return '';
    }
}
