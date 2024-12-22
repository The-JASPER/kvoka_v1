<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\Entry;

class EntryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'Текст',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('color', ChoiceType::class, [
                'label' => 'Цвет',
                'choices' => [
                    'Красный' => 'red',
                    'Синий' => 'blue',
                    'Зеленый' => 'green',
                    'Фиолетовый' => 'purple',
                ],
            ])
            ->add('shape', ChoiceType::class, [
                'label' => 'Фигура',
                'choices' => [
                    'Треугольник' => 'triangle',
                    'Круг' => 'circle',
                    'Квадрат' => 'square',
                    'Ромб' => 'diamond',
                ],
            ])
            ->add('images', FileType::class, [
                'label' => 'Изображения',
                'multiple' => true,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Допустимы только файлы JPG и PNG.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entry::class,
        ]);
    }
}
