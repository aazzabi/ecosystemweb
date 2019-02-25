<?php

namespace EcoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AnnonceRepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')->add('description',TextareaType::class)->add('categorie', ChoiceType::class, [
            'choices'  => [
                'Téléphone' => "Téléphone",
                'Electroménager' => "Electroménager",
                'Immobilier' => "Immobilier",
            ],
        ])->add('annoncerepPhoto', VichFileType::class, [
            'required' => false,
            'allow_delete' => true,
            'download_link' => true
        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EcoBundle\Entity\AnnonceRep'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ecobundle_annoncerep';
    }


}
