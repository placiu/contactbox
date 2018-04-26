<?php

namespace ContactBundle\Form;

use ContactBundle\Entity\Phone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class deletePhoneForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phones', EntityType::class, ['class' => Phone::class, 'mapped' => false, 'choice_label' => 'number'])
            ->add('delete', SubmitType::class)
        ;
    }
}