<?php
/**
 * Created by PhpStorm.
 * User: Rania
 * Date: 22/02/2019
 * Time: 18:32
 */

namespace EcoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class rechercheEventType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('lieu')->add('save',SubmitType::class);
    }



}