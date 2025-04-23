<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\CompanyRegisterLeadDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyRegisterLeadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Imię',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Jan',
                    'class' => 'mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Kowalski',
                    'class' => 'mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm',
                ],
            ])
            ->add('nip', TextType::class, [
                'label' => 'NIP',
                'required' => true,
                'attr' => [
                    'placeholder' => '1234567890',
                    'maxlength' => 10, // Basic length validation
                    'pattern' => '\d{10}', // Basic pattern validation
                    'class' => 'mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm',
                ],
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Numer telefonu',
                'required' => true,
                'attr' => [
                    'placeholder' => '+48 123 456 789',
                    'class' => 'mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'placeholder' => 'jan.kowalski@example.com',
                    'class' => 'mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanyRegisterLeadDto::class,
        ]);
    }
} 