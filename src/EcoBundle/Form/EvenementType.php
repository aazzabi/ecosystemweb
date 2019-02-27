<?php

namespace EcoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lieu',TextType::class,[
                'required' =>false,
            ])
            ->add('date')
            ->add('evtCover', VichFileType::class, [
                'required' =>false,
                'allow_delete' => true,
                'download_link' => true
            ])
            ->add('categorie', EntityType::class, array(
                'class'=>'EcoBundle\Entity\CategorieEvts',
                'choice_label'=>'libelle',
                'multiple'=>false
            ))
            ->add('titre',TextType::class,[
                'required' =>false,
            ])
            ->add('description',TextType::class,[
                'required' =>false,
            ]);
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
