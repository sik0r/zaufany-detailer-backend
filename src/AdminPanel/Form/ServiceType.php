<?php

declare(strict_types=1);

namespace App\AdminPanel\Form;

use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @extends AbstractType<Service>
 */
class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa usługi',
                'required' => true,
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['max' => 240])],
            ])
            ->add('parent', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Usługa nadrzędna',
                'placeholder' => 'Wybierz usługę nadrzędną (opcjonalnie)',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
