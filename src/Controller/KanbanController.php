<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class KanbanController extends AbstractController
{
    #[Route('/kanban', name: 'app_kanban')]
    public function index(TaskRepository $repoTask): Response
    {
        $tasks = $repoTask->findBy([], ['id' => 'ASC']);

        $columns = [
            'todo' => [],
            'doing' => [],
            'done' => [],
            'urgent' => [],
        ];

        foreach ($tasks as $task) 
        {
            $key = strtolower((string) $task->getStatus());
            $key = array_key_exists($key, $columns) ? $key : 'todo';
            $columns[$key][] = $task;

        }

        return $this->render('kanban/index.html.twig', [
            'columns' => $columns,
        ]);
    }


    #[Route('/kanban/save-order', name: 'kanban_save_order', methods: ['POST'])]
    public function saveOrder(
        Request $req,
        TaskRepository $repo,
        EntityManagerInterface $em,
        CsrfTokenManagerInterface $csrf
    ): JsonResponse {
        $data  = json_decode($req->getContent(), true) ?? [];
        $token = (string)($data['_token'] ?? '');

        // проверка CSRF
        if (!$csrf->isTokenValid(new CsrfToken('kanban_order', $token))) {
            return new JsonResponse(['ok' => false, 'error' => 'bad_csrf'], 400);
        }

        // обновляем порядок и статус
        $columns = ['todo','doing','done','urgent'];
        foreach ($columns as $col) {
            $ids = array_map('intval', (array)($data[$col] ?? []));
            foreach ($ids as $i => $id) {
                if ($task = $repo->find($id)) {
                    $task->setStatus($col);
                    $task->setPosition($i);
                }
            }
        }
        $em->flush();

        return new JsonResponse(['ok' => true]);
    }
}
