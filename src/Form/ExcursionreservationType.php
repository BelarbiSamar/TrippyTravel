<?php

namespace App\Form;

use App\Entity\Excursionreservation;
use App\Entity\Excursion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExcursionreservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix')
            ->add('excursion', EntityType::class,
                [
                    'class' => Excursion::class,
                    'choice_label' => 'libelle',
                    'required' => true,
                ])
            ->add('user', EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'firstname',
                    'required' => true,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Excursionreservation::class,
        ]);
    }
}
