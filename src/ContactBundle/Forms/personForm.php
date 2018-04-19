<?php

namespace ContactBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class personForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(isset($option['url'])) $builder->setAction($options['url']);
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('description', TextareaType::class)
            ->add('image_path', FileType::class, array('data_class' => null, 'required' => false))
            ->add('save', SubmitType::class)
        ;
    }
}