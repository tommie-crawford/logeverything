<?php

namespace App\Form;

use App\Entity\FishingLog;
use App\Enum\FishType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class FishingLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('type', EnumType::class, [
                'class' => FishType::class,
                'label' => 'Type vis',
                'required' => true,
                'choice_label' => function (FishType $fishType): string {
                    return ucfirst($fishType->value);
                },
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Gewicht (kg)',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                ],
            ])
            ->add('length', NumberType::class, [
                'label' => 'Lengte (cm)',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                ],
            ])
            ->add('images', FileType::class, [
                'label' => 'Afbeeldingen',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new Image([
                                'maxSize' => '5M',
                            ]),
                        ],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FishingLog::class,
        ]);
    }
}
