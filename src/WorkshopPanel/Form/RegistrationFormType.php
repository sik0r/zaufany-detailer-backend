<?php

declare(strict_types=1);

namespace App\WorkshopPanel\Form;

use App\WorkshopPanel\Dto\RegisterCompanyRequestDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName', TextType::class, [
                'label' => 'Nazwa firmy',
                'attr' => ['placeholder' => 'Pełna nazwa Twojej firmy'],
            ])
            ->add('employeeFirstName', TextType::class, [
                'label' => 'Imię właściciela/osoby kontaktowej',
                'attr' => ['placeholder' => 'Jan'],
            ])
            ->add('employeeLastName', TextType::class, [
                'label' => 'Nazwisko właściciela/osoby kontaktowej',
                'attr' => ['placeholder' => 'Kowalski'],
            ])
            ->add('nip', TextType::class, [
                'label' => 'NIP firmy',
                'attr' => ['placeholder' => '1234567890', 'maxlength' => 10],
            ])
            ->add('regon', TextType::class, [
                'label' => 'REGON firmy',
                'attr' => ['placeholder' => '123456789 lub 12345678901234', 'maxlength' => 14],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adres e-mail (do logowania)',
                'attr' => ['placeholder' => 'kontakt@twojafirma.pl'],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numer telefonu kontaktowego',
                'attr' => ['placeholder' => '+48 123 456 789'],
            ])
            ->add('companyStreet', TextType::class, [
                'label' => 'Ulica i numer',
                'attr' => ['placeholder' => 'ul. Przykładowa 10/5'],
            ])
            ->add('companyPostalCode', TextType::class, [
                'label' => 'Kod pocztowy',
                'attr' => ['placeholder' => '00-000'],
            ])
            ->add('companyCity', TextType::class, [
                'label' => 'Miasto',
                'attr' => ['placeholder' => 'Warszawa'],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Hasła muszą być identyczne.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Hasło'],
                'second_options' => ['label' => 'Potwierdź hasło'],
            ])
             ->add('termsAccepted', CheckboxType::class, [
                'mapped' => true,
                'label' => 'Akceptuję regulamin serwisu', // Add link to terms later
                 'required' => true, // Make sure it's checked
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegisterCompanyRequestDto::class,
        ]);
    }
} 