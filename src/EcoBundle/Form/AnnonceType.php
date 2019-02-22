<?php

namespace EcoBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AnnonceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')
            ->add('description')
            ->add('prix')
            ->add('region',ChoiceType::class,
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
            ->add('annoncephoto', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_link' => true
            ])
            ->add('categorie',EntityType::class,array(
                    'class'=>'EcoBundle:CategorieAnnonce',
                    'multiple'=>false)
            );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EcoBundle\Entity\Annonce'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ecobundle_annonce';
    }


}
