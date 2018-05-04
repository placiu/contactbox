<?php

namespace ContactBundle\Form;

use ContactBundle\Entity\Mail;
use ContactBundle\Entity\Phone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteMailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mails', EntityType::class, ['class' => Mail::class, 'mapped' => false, 'choice_label' => 'name'])
            ->add('delete', SubmitType::class)
        ;
    }
}