<?php

namespace EcoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class LivreurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required'=>false,
                'label' => 'Nom',
            ],'first')
            ->add('prenom', TextType::class, [
                'required'=>false,
                'label' => 'Prenom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required'=>false,
            ])
            ->add('userPhoto', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_link' => true
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'required'=>true,
            ])
            ->add('zone',TextType::class,[
                'required' =>false,
            ])->add('disponibilite',TextType::class,[
                'required' =>false,
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required'=>false,
                'options' => array(
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'autocomplete' => 'new-password',
                    ),
                ),
                'first_options' => array('label' => 'MOT DE PASSE *'),
                'second_options' => array('label' => 'RÉPÉTER LE MOT DE PASSE *'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EcoBundle\Entity\Livreur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ecobundle_livreur';
    }


}
