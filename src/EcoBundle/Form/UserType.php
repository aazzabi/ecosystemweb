<?php

namespace EcoBundle\Form;

use EcoBundle\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required'=>true,
                'label' => 'entity.user.nom',
            ],'first')
            ->add('prenom', TextType::class, [
                'required'=>true,
                'label' => 'entity.user.prenom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'entity.user.email',
                'required'=>true,
            ])
            ->add('username', TextType::class, [
                'label' => 'entity.user.username',
                'required'=>true,
            ])
            ->add('roles', ChoiceType::class, array(
                'label' => 'entity.user.roles',
                'choices'   => array(
                    'Examinateur'   => 'ROLE_EXAMIN',
                    'Admin'   => 'ROLE_ADMIN',
                    'Consultant'   => 'ROLE_CONSULT',
                    'Saisie'   => 'ROLE_SAISIE',
                ),
                'required'=>true,
                'multiple'  => true
            ))
            ->add('group', EntityType::class, array(
                'label' => 'entity.user.group',
                'class' => Group::class,
                'multiple'=>false,
                'required'=>true,
            ))
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
            'data_class' => 'EcoBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eppbundle_user';
    }


}
