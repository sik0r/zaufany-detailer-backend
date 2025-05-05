<?php

declare(strict_types=1);

namespace App\AdminPanel\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @extends AbstractType<CompanyEmployeeType>
 */
class CompanyEmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Company Fields
            ->add('company_name', TextType::class, [
                'label' => 'Nazwa firmy',
                'required' => true,
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('company_nip', TextType::class, [
                'label' => 'NIP',
                'required' => true,
                // Consider making readonly if always pre-filled
                'attr' => ['readonly' => true],
                'constraints' => [
                    new Assert\NotBlank(),
                    // TODO: Add NIP validator constraint
                    // new Assert\Regex(['pattern' => '/^\d{10}$/', 'message' => 'NIP musi składać się z 10 cyfr.'])
                ],
            ])
            ->add('company_regon', TextType::class, [
                'label' => 'REGON',
                'required' => false, // REGON is nullable in entity
                // TODO: Add REGON validator constraint
            ])
            ->add('company_street', TextType::class, [
                'label' => 'Ulica i numer',
                'required' => true,
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('company_postalCode', TextType::class, [
                'label' => 'Kod pocztowy',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    // TODO: Add Postal Code validator constraint (e.g., regex for XX-XXX)
                    // new Assert\Regex(['pattern' => '/^\d{2}-\d{3}$/', 'message' => 'Nieprawidłowy format kodu pocztowego (oczekiwano XX-XXX).'])
                ],
                'attr' => ['placeholder' => '00-000'],
            ])
            ->add('company_city', TextType::class, [
                'label' => 'Miasto',
                'required' => true,
                'constraints' => [new Assert\NotBlank()],
            ])

            // Employee Fields
            ->add('employee_firstName', TextType::class, [
                'label' => 'Imię pracownika',
                'required' => true,
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('employee_lastName', TextType::class, [
                'label' => 'Nazwisko pracownika',
                'required' => true,
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('employee_email', EmailType::class, [
                'label' => 'Email pracownika',
                'required' => true,
                'attr' => ['readonly' => true], // Usually pre-filled and shouldn't be changed here
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ])
            ->add('employee_phoneNumber', TextType::class, [
                'label' => 'Telefon pracownika',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    // TODO: Add phone number validator constraint
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'mapped' => false, // Not mapped directly to a single entity
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id' => 'process_lead_item',
        ]);
    }
}
