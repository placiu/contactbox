<?php

namespace ContactBundle\Form;

use ContactBundle\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class addressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', TextType::class, ['attr' => ['class' => 'mb-2 form-control'], 'label' => false])
            ->add('street', TextType::class, ['attr' => ['class' => 'mb-2 form-control'], 'label' => false])
            ->add('number', IntegerType::class, ['attr' => ['class' => 'mb-2 form-control'], 'label' => false])
            ->add('flat', IntegerType::class, ['attr' => ['class' => 'form-control'], 'label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Address::class,
        ));
    }
}