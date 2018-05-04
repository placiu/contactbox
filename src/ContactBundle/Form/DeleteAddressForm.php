<?php

namespace ContactBundle\Form;

use ContactBundle\Entity\Address;
use ContactBundle\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DeleteAddressForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addresses', EntityType::class, ['class' => Address::class, 'mapped' => false, 'choice_label' => 'city'])
            ->add('delete', SubmitType::class)
        ;
    }
}