<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class EntryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', null, [
                'constraints' => [new NotBlank()],
            ])
            ->add('email', null, [
                'constraints' => [new NotBlank()],
            ])
            ->add('color', ChoiceType::class, [
                'choices' => [
                    'Красный' => 'red',
                    'Синий' => 'blue',
                    'Зеленый' => 'green',
                    'Фиолетовый' => 'purple',
                ],
                'constraints' => [new NotBlank()],
            ])
            ->add('shape', ChoiceType::class, [
                'choices' => [
                    'Треугольник' => 'triangle',
                    'Круг' => 'circle',
                    'Квадрат' => 'square',
                    'Ромб' => 'diamond',
                ],
                'constraints' => [new NotBlank()],
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                    ]),
                ],
            ]);
    }
}