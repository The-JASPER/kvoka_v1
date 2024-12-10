<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(): Response
    {
        // Если пользователь уже аутентифицирован, перенаправить его на домашнюю страницу
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        // Этот метод остаётся пустым, так как Symfony обрабатывает логику выхода
        throw new \LogicException('Этот метод должен быть пустым - Symfony обработает логику выхода.');
    }
}
