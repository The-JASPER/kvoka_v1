<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Form\EntryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EntryController extends AbstractController
{
    #[Route("/", name: "app_entry")]
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $entry = new Entry();
        $form = $this->createForm(EntryFormType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            $imagePaths = [];

            foreach ($images as $image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                $image->move($this->getParameter('images_directory'), $newFilename);
                $imagePaths[] = $newFilename;
            }

            $entry->setImages($imagePaths);
            $entityManager->persist($entry);
            $entityManager->flush();

            $this->addFlash('success', 'Запись сохранена');
            return $this->redirectToRoute('app_entry');
        }

        return $this->render('entry/index.html.twig', [
            'entryForm' => $form->createView(),
        ]);
    }
}