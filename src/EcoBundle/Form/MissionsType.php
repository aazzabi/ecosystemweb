<?php

namespace EcoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;
class MissionsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('lieu')
            ->add('objectif')
            ->add('description')
            ->add('date')
            ->add('dateLimite')
            ->add('evtCover', VichFileType::class, [
                'required' =>false,
                'allow_delete' => true,
                'download_link' => true
            ])
            ->add('categorie', EntityType::class, array(
                'class'=> 'EcoBundle\Entity\CategorieMission',
                'choice_label'=>'libelle',
                'multiple'=>false
            ));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EcoBundle\Entity\Missions'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ecobundle_evenement';
    }


}
