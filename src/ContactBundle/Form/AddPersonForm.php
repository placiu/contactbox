<?php

namespace ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class AddPersonForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('description', TextType::class)
            ->add('image_path', FileType::class, array('data_class' => null, 'required' => false))
            ->add('addresses', CollectionType::class, ['entry_type' => AddressType::class, 'entry_options' => ['label' => 'Address', 'attr' => ['class' => 'mb-4']]])
            ->add('phones', CollectionType::class, ['entry_type' => PhoneType::class, 'entry_options' => ['label' => 'Phone Number', 'attr' => ['class' => 'mb-4']]])
            ->add('mails', CollectionType::class, ['entry_type' => MailType::class, 'entry_options' => ['label' => 'Email', 'attr' => ['class' => 'mb-4']]])
            ->add('save', SubmitType::class)
        ;
    }
}