<?php

namespace ContactBundle\Form;

use ContactBundle\Entity\Phone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, ['attr' => ['class' => 'mb-2 form-control'], 'label' => false])
            ->add('type', TextType::class, ['attr' => ['class' => 'form-control'], 'label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Phone::class,
        ));
    }
}