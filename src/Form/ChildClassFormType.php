<?php

namespace App\Form;

use App\Entity\ChildClass;
use App\Entity\ParentClass;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChildClassFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('parents', EntityType::class, [
                'class'        => ParentClass::class,
                'choice_label' => 'name',
                'label'        => 'Parents',
                'expanded'     => false,
                'multiple'     => true,
                'required'     => false,
                'placeholder'  => '(-)'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save child'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChildClass::class,
        ]);
    }
}
