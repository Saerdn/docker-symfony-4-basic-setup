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

class ParentClassFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('children', EntityType::class, [
                'class'        => ChildClass::class,
                'choice_label' => 'name',
                'label'        => 'Children',
                'expanded'     => false,
                'multiple'     => true,
                'required'     => false,
                'placeholder'  => '(-)'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save parent'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ParentClass::class,
        ]);
    }
}
