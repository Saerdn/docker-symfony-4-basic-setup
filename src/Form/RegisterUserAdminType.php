<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterUserAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('surname', TextType::class)
            ->add('email', EmailType::class, [
                'label' => 'Mail'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat password']
            ])
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'required' => false,
                'choices' => [
                    'ROLE_ADMIN' =>'ROLE_ADMIN'
                ]
            ])
            ->add('is_active', null, [
                'label' => 'Is active'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save user',
                'attr' => [
                    'class' => 'btn btn-primary waves-effect waves-light'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
