<?php

namespace ContactBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class phoneForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('phones', CollectionType::class, array('entry_type' => TextType::class, 'label' => false, 'entry_options' => array('label' => false)))
                ->add('save', SubmitType::class);
    }
}