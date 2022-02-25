<?php

namespace App\Form;

use App\Entity\Attraction;
use App\Entity\CategorieAttraction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttractionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('localisation')
            ->add('horraire')
            ->add('prix')
            ->add('id_categorie',EntityType::class,
            [
                'class' => CategorieAttraction::class,
                'choice_label' => 'libelle',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attraction::class,
            'required' => false,
        ]);
    }
}
