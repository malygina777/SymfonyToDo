<?php

namespace App\Controller;

use Throwable;

use App\Entity\AppUser;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function register(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer,
        ): Response
    {
         $user = new AppUser();
         
         // 1. Создаём форму
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // 2. Проверяем отправку и валидацию
        if ($form->isSubmitted() && $form->isValid()) {
            // 3. Берём пароль из формы (mapped=false → отдельно)
            $plainPassword = $form->get('plainPassword')->getData();

            // 4. Хешируем пароль
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);

            // 5. Сохраняем в БД
            $manager->persist($user);
            $manager->flush();

            //email
             $email = (new TemplatedEmail())
                 ->from('no-reply@todolist.local')
                 ->to($user->getEmail())
                 ->subject('Bienvenue')
                 ->htmlTemplate('emails/welcome.html.twig');

             $mailer->send($email);


            //6 flash message
            $this->addFlash('success', 'Votre compte a été créé avec succés. Bienvenue!');

            $request->getSession()->migrate(true);

            // 7. Перенаправляем (например, на логин)
            return $this->redirectToRoute('app_login'); 
        }

        // 7. Рендерим шаблон с формой
        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
