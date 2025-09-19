<?php

namespace App\Controller;

use App\Form\AvatarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile')]
final class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile', methods: ['GET'])]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Просто отрисовываем страницу с формой
        $form = $this->createForm(AvatarType::class);

        return $this->render('profile/index.html.twig', [
            'avatarForm' => $form->createView(),
        ]);
    }

    #[Route('/avatar', name: 'app_profile_avatar', methods: ['POST'])]
    public function upload(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        #[Autowire(param: 'avatars_dir')] string $avatarsDir
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // 1) Берём и валидируем форму
        $form = $this->createForm(AvatarType::class);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addFlash('danger', 'Formulaire invalide.');
            return $this->redirectToRoute('app_profile');
        }

        // 2) Достаём файл
        $file = $form->get('avatarFile')->getData();
        if (!$file) {
            $this->addFlash('warning', 'Choisissez une image.');
            return $this->redirectToRoute('app_profile');
        }

        // 3) Делаем имя и сохраняем
        $base   = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safe   = (string) $slugger->slug($base);
        $name   = $safe.'-'.uniqid().'.'.$file->guessExtension();

        if (!is_dir($avatarsDir)) {
            mkdir($avatarsDir, 0775, true);
        }
        $file->move($avatarsDir, $name);

        // 4) Обновляем пользователя
        /** @var \App\Entity\AppUser $user */
        $user = $this->getUser();

        // опционально: удалить старый файл
        if ($user->getAvatar()) {
            $old = $avatarsDir.'/'.$user->getAvatar();
            if (is_file($old)) { @unlink($old); }
        }

        $user->setAvatar($name);
        $em->flush();

        $this->addFlash('success', 'Avatar mis à jour ✔️');
        return $this->redirectToRoute('app_profile');
    }
}