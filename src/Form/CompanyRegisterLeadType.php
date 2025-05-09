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

/**
 * @extends AbstractType<CompanyRegisterLeadDto>
 */
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
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Kowalski',
                ],
            ])
            ->add('nip', TextType::class, [
                'label' => 'NIP',
                'required' => true,
                'attr' => [
                    'placeholder' => '1234567890',
                    'maxlength' => 10, // Basic length validation
                    'pattern' => '\d{10}', // Basic pattern validation
                ],
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Numer telefonu',
                'required' => true,
                'attr' => [
                    'placeholder' => '+48 123 456 789',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'placeholder' => 'jan.kowalski@example.com',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanyRegisterLeadDto::class,
        ]);
    }
}
