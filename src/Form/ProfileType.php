<?php

namespace App\Form;

use App\Entity\User;
use COM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('bio', TextareaType::class)
            ->add('telephone')
            ->add('address')
            ->add('naissance')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme'
                ]
            ])
            ->add('imageFile', VichImageType::class,
            [
                'label' => 'photo de profile',
            ])
            //->add('roles')
            //->add('password')
            //->add('isVerified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'required' => false
        ]);
    }
}
