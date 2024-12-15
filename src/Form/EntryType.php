<?php

namespace App\Form;

use App\Entity\Entry;
use App\Entity\Color;
use App\Entity\Shape;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('color', EntityType::class, [
                'class' => Color::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите цвет',
            ])
            ->add('shape', EntityType::class, [
                'class' => Shape::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите форму',
            ])
            ->add('images', FileType::class, [
                'label' => 'Загрузите изображения',
                'multiple' => true,
                'mapped' => false, // Указываем, что поле не связано напрямую с сущностью
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entry::class,
        ]);
    }
}
