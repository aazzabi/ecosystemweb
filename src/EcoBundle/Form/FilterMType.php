<?php
/**
 * Created by PhpStorm.
 *
 * Date: 20/02/2019
 * Time: 15:47
 */

namespace EcoBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FilterMType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lieu')
        ->add('save',SubmitType::class);
    }
}