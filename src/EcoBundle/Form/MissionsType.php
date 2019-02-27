<?php

namespace EcoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('lieu',ChoiceType::class,
                array('choices'=>array
                (   'Tunis'=>"Tunis",
                    'Ariana'=>"Ariana",
                    'Manouba'=>"Manouba",
                    'Ben Arous'=>"Ben Arous",
                    'Bizerte'=>"Bizerte",
                    'Béja'=>"Béja",
                    'Jendouba'=>"Jendouba",
                    'Kef'=>"Kef",
                    'Siliana'=>"Siliana",
                    'Kasserine'=>"Kasserine",
                    'Sidi Bouzid'=>"Sidi Bouzid",
                    'Gafsa'=>"Gafsa",
                    'Tozeur'=>"Tozeur",
                    'Kébili'=>"Kébili",
                    'Tataouine'=>"Tataouine",
                    'Médenine'=>"Médenine",
                    'Gabès'=>"Gabès",
                    'Sfax'=>"Sfax",
                    'Kairouan'=>"Kairouan",
                    'Mahdia'=>"Mahdia",
                    'Monastir'=>"Monastir",
                    'Sousse'=>"Sousse",
                    'Zaghouan'=>"Zaghouan",
                    'Nabeul'=>"Nabeul",
                ),))
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
