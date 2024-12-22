<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Form\EntryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * Отображение списка записей.
     */
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Получение всех записей из базы данных
        $entries = $entityManager->getRepository(Entry::class)->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'entries' => $entries,
        ]);
    }

    /**
     * Просмотр записи по ID.
     */
    #[Route('/admin/entry/{id}', name: 'admin_entry_view')]
    public function view(int $id, EntityManagerInterface $entityManager): Response
    {
        // Поиск записи по ID
        $entry = $entityManager->getRepository(Entry::class)->find($id);

        if (!$entry) {
            throw $this->createNotFoundException('Запись не найдена');
        }

        return $this->render('admin/view.html.twig', [
            'entry' => $entry,
        ]);
    }

    /**
     * Редактирование записи.
     */
    #[Route('/admin/entry/{id}/edit', name: 'admin_entry_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Поиск записи по ID
        $entry = $entityManager->getRepository(Entry::class)->find($id);

        if (!$entry) {
            throw $this->createNotFoundException('Запись не найдена');
        }

        // Создание формы для редактирования
        $form = $this->createForm(EntryFormType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Сохранение изменений
            $entityManager->flush();

            $this->addFlash('success', 'Запись обновлена!');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit.html.twig', [
            'entryForm' => $form->createView(),
        ]);
    }
}
