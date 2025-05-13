<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\Workshop\CreateWorkshopDto;
use App\Entity\Locality;
use App\Entity\Voivodeship;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @extends AbstractType<CreateWorkshopDto>
 */
class WorkshopCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa',
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['max' => 240])],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [new Assert\NotBlank(), new Assert\Email(), new Assert\Length(['max' => 255])],
            ])
            ->add('street', TextType::class, [
                'label' => 'Ulica i numer',
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['max' => 255])],
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Kod pocztowy',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 6]),
                    new Assert\Regex(['pattern' => '/^\d{2}-\d{3}$/', 'message' => 'Poprawny format kodu pocztowego: XX-XXX']),
                ],
            ])
            ->add('region', EntityType::class, [
                'class' => Voivodeship::class,
                'choice_label' => 'name',
                'label' => 'Województwo',
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('city', EntityType::class, [
                'class' => Locality::class,
                'choice_label' => 'name',
                'label' => 'Miasto',
                'constraints' => [new Assert\NotBlank()],
                'group_by' => function (Locality $locality) {
                    return $locality->getVoivodeship()->getName();
                },
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC')
                        ->setMaxResults(100)
                    ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateWorkshopDto::class,
        ]);
    }
}
