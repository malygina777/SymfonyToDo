<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
