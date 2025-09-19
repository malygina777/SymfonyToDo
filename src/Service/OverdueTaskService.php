<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

final class OverdueTaskService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
    ) {}

    public function process(): int
    {
        $now = new \DateTimeImmutable('today'); // наступил день дедлайна
        // Найти все не-выполненные, не-urgent, у которых dueAt <= сегодня
        $qb = $this->em->getRepository(Task::class)->createQueryBuilder('t');
        $tasks = $qb
            ->andWhere('t.dueAt IS NOT NULL AND t.dueAt <= :now')
            ->andWhere('t.status != :done')
            ->andWhere('t.status != :urgent')
            ->setParameter('now', $now)
            ->setParameter('done', 'done')
            ->setParameter('urgent', 'urgent')
            ->getQuery()->getResult();

        foreach ($tasks as $task) {
           
            $task->setStatus('urgent');

            $user = $task->getOwner();
            if ($user && $user->getEmail()) {
                $email = (new TemplatedEmail())
                    ->from(new Address('no-reply@todolist.local', 'TodoList'))
                    ->to($user->getEmail())
                    ->subject('⚠️ Tâche en retard')
                    ->htmlTemplate('emails/overdue_task.html.twig')
                    ->context([
                        'task' => $task,
                    ]);
                $this->mailer->send($email);
            }
        }

        if ($tasks) { $this->em->flush(); }

        return \count($tasks);
    }
}