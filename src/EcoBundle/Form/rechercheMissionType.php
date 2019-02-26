<?php
/**
 * Created by PhpStorm.
 *
 * Date: 22/02/2019
 * Time: 18:32
 */

namespace EcoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class rechercheMissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lieu')->add('save',SubmitType::class);
    }

}