<?php

namespace App\Form;

use App\Entity\Hotelimage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class HotelimageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add(
            'imageFile',
            VichImageType::class,
            array(
                'attr' => array(
                    'placeholder' => 'Images'
                ),
                'required' => false,
                'label'        => 'Images :',
            )
        )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotelimage::class,
        ]);
    }
}
