<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Entity\Image;
use App\Form\EntryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntryController extends AbstractController
{
    #[Route('/entry/new', name: 'entry_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entry = new Entry();
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Обработка изображений
            $imageFiles = $form->get('images')->getData();
            foreach ($imageFiles as $imageFile) {
                $filename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('images_directory'), $filename);

                $image = new Image();
                $image->setFilename($filename);
                $entry->addImage($image);
            }

            $entityManager->persist($entry);
            $entityManager->flush();

            return $this->redirectToRoute('entry_success'); // Замените на ваш маршрут
        }

        return $this->render('entry/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
