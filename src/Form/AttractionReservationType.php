<?php

namespace App\Form;

use App\Entity\AttractionReservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttractionReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix')
            ->add('status')
            ->add('createdAt')
            ->add('start')
            ->add('end')
            ->add('attraction')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AttractionReservation::class,
        ]);
    }
}
