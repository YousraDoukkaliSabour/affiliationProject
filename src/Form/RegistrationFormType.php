<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => true,
                'attr' => ['placeholder' => 'Email'],
                'constraints' => [
                    new Email(['message' => 'Please enter a valid email address.']),
                    new NotBlank(['message' => 'Please enter your email address.']),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'First Name']
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Last Name']
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'required' => true,
                'attr' => ['placeholder' => 'Password'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your password.']),
                    new Length(['min' => 8, 'minMessage' => 'Your password must be at least {{ limit }} characters long.']),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false, // Ne sera pas mappé à l'entité User
                'attr' => ['placeholder' => 'Confirm Password'],
                'constraints' => [
                    new NotBlank(['message' => 'Please confirm your password.']),
                    new Length(['min' => 8, 'minMessage' => 'Your password must be at least {{ limit }} characters long.']),
                ],
            ]) // Fermez le tableau de configuration pour le champ confirmPassword ici
            ->add('phoneNumber', TelType::class, [
                'label' => false,
                'required' => true,
                'attr' => ['placeholder' => 'Phone Number'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your phone number.']),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('address', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => ['placeholder' => 'Address'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your address.']),
                ],
            ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }


}