<?php

namespace EcoBundle\Form;

use EcoBundle\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class RespSocType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class, [
            'required'=>true,
            'label' => 'Nom',
        ],'first')
            ->add('prenom', TextType::class, [
                'required'=>true,
                'label' => 'Prenom'
            ])
            ->add('group', EntityType::class, array(
                'label' => 'Group',
                'class' => Group::class,
                'multiple'=>false,
                'required'=>true,
            ))
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required'=>true,
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
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EcoBundle\Entity\RespSoc'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ecobundle_respsoc';
    }


}
