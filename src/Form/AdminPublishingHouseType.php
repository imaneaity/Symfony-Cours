<?php

namespace App\Form;

use App\Entity\PublishingHouse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminPublishingHouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de la maison d'édition : ",
                'required' => true, ])


            ->add('description', TextareaType::class, [
                'label' => "info sur la maison d'edition ",
                'required' => false,])
                
            ->add('country', CountryType::class, [
                'label' => "pays de la maison d'edition ",
                'required' => false,])

                ->add('send', SubmitType::class, [
                    'label' => 'Envoyer',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PublishingHouse::class,
        ]);
    }
}
