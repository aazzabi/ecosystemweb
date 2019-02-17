<?php

namespace EcoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lieu')->add('date')->add('createdBy')->add('categorie',EntityType::class,
                array('class'=>'EcoBundle\Entity\CategorieEvts',
                    'choice_label'=>'libelle',
                    'multiple'=>false))->add('titre')->add('description')->add('participants');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EcoBundle\Entity\Evenement'
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
