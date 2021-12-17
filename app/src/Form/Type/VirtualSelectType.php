<?php

namespace App\Form\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VirtualSelectType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {

//        $resolver->setDefaults(array(
//                                   'choices' => array(
//                                       'Standard Shipping' => 'standard',
//                                       'Expedited Shipping' => 'expedited',
//                                       'Priority Shipping' => 'priority',
//                                   ),
//                               ));
    }

    public function getParent()
    {
//        return ChoiceType::class;
        return EntityFilterType::class;
//        return EntityType::class;
    }

    public function getBlockPrefix()
    {
        return 'virtualSelect';
    }
}