<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Form\EntryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $entry = new Entry();
        $form = $this->createForm(EntryFormType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Проверка и создание директории для изображений
            $imageDirectory = $this->getParameter('kernel.project_dir') . '/public/images';
            if (!is_dir($imageDirectory)) {
                mkdir($imageDirectory, 0777, true);
            }

            $images = $form->get('images')->getData();
            $imagePaths = [];

            foreach ($images as $image) {
                // Генерация уникального имени файла
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    // Перемещение файла в директорию
                    $image->move($imageDirectory, $newFilename);
                    $imagePaths[] = $newFilename;
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Не удалось загрузить изображение: ' . $e->getMessage());
                    return $this->redirectToRoute('app_home');
                }
            }

            $entry->setImages($imagePaths);

            // Сохранение записи
            $entityManager->persist($entry);
            $entityManager->flush();

            $this->addFlash('success', 'Запись сохранена!');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'entryForm' => $form->createView(),
        ]);
    }
}
