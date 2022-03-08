<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Vich\UploaderBundle\Form\Type\VichFileType;


class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('localisation')
            ->add('nbetoile')
            ->add('nbchdispo')
            ->add('description_hotel')
            ->add(
                'Hotelimage',
                CollectionType::class,
                array(
                    'entry_type' => HotelimageType::class,
                    'by_reference' => false,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'label' => 'Image(s) :',
                    'prototype' => true,
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_hotel';
    }
}
