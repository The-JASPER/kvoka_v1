<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Color;
use App\Entity\Shape;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Список цветов
        $colors = ['Красный', 'Синий', 'Зеленый', 'Фиолетовый'];
        foreach ($colors as $colorName) {
            $color = new Color();
            $color->setName($colorName);
            $manager->persist($color);
        }

        // Список геометрических фигур
        $shapes = ['Треугольник', 'Круг', 'Квадрат', 'Ромб'];
        foreach ($shapes as $shapeName) {
            $shape = new Shape();
            $shape->setName($shapeName);
            $manager->persist($shape);
        }

        // Сохранение данных в базе
        $manager->flush();
    }
}
