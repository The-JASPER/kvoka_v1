<?php
// src/Security/AppCustomAuthenticator.php

   namespace App\Security;

   use App\Entity\User;
   use Doctrine\ORM\EntityManagerInterface;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\Security\Core\Exception\AuthenticationException;
   use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
   use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
   use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CredentialsBadge;
   use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
   use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
   use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Измените это
   use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

   class AppCustomAuthenticator extends AbstractAuthenticator
   {
       private EntityManagerInterface $entityManager;
       private UserPasswordHasherInterface $passwordHasher; // Измените это

       public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher) // Измените это
       {
           $this->entityManager = $entityManager;
           $this->passwordHasher = $passwordHasher; // Измените это
       }

       public function supports(Request $request): ?bool
       {
           return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
       }

       public function authenticate(Request $request): Passport
       {
           $username = $request->request->get('username');
           $password = $request->request->get('password');

           if (empty($username) || empty($password)) {
               throw new CustomUserMessageAuthenticationException('Введите имя пользователя и пароль.');
           }

           $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

           if (!$user) {
               throw new CustomUserMessageAuthenticationException('Пользователь не найден.');
           }

           // Проверка пароля
           if (!$this->passwordHasher->isPasswordValid($user, $password)) { // Измените это
               throw new CustomUserMessageAuthenticationException('Неверный пароль.');
           }

           return new Passport(
               new UserBadge($user->getUsername()),
               new CredentialsBadge($password)
           );
       }

       public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?Response
       {
           return null; // null означает, что мы не хотим перенаправлять, а просто продолжаем выполнение
       }

       public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
       {
           return new Response($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
       }
   }