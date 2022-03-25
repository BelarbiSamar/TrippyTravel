<?php

namespace App\Form;

use App\Entity\Excursion;
use App\Entity\Excursioncategorie;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ExcursionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('duration',TextType::class,[
                    'attr' => ['label' => 'durée']
                ]
            )
            ->add('description', CKEditorType::class)
            ->add('programme', CKEditorType::class)
            ->add('ville')
            ->add('prix')
            ->add('localisation')
            ->add('excursioncategorie', EntityType::class,
                [
                    'class' => Excursioncategorie::class,
                    'choice_label' => 'libelle',
                    'required' => true,
                ])
            ->add(
                'excursionimages',
                CollectionType::class,
                array(
                    'entry_type' => ExcursionimageType::class,
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
            'data_class' => Excursion::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_excursion';
    }

}
