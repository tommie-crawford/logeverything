<?php

namespace App\Form;

use App\Entity\DiveLog;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class DiveLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('location')
            ->add('notes')
            // 🔽 nieuw veld voor uploads
            ->add('images', FileType::class, [
                'label' => 'Picture\'s',
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
            ->add('maxDepth', IntegerType::class, [
                'label' => 'Max depth',
                'required' => TRUE,
            ])
            ->add('course', null, [
                'label' => 'Course',
                'required' => FALSE,
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duration',
                'required' => TRUE,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DiveLog::class,
        ]);
    }
}
