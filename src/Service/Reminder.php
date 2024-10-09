<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class Reminder
{
    private $em;
    private $template;
    private $mailer;
    private $reminderEmailFrom;
    private $reminderEmailTo;

    public function __construct(EntityManagerInterface $em, \Twig_Environment $template, \Swift_Mailer $mailer, $reminderEmailFrom, $reminderEmailTo){
        $this->em = $em;
        $this->template = $template;
        $this->mailer = $mailer;
        $this->reminderEmailFrom = $reminderEmailFrom;
        $this->reminderEmailTo = $reminderEmailTo;
    }
    
    public function remind()
    {
        $tasks = $this->em->getRepository(Task::class)->findAllToRemind(new \DateTime());        
        
        foreach ($tasks as $task) {
           $message = (new \Swift_Message("A task must be realised"))
                ->setFrom($this->reminderEmailFrom)
                ->setTo($this->reminderEmailTo)
                ->setBody(
                    $this->template->render(
                        'emails/reminder.html.twig',
                        ['task' => $task]
                    ),
                    'text/html'
                )
                ->addPart(
                    $this->template->render(
                        'emails/reminder.txt.twig',
                        ['task' => $task]
                    ),
                    'text/plain'
                );

            $this->mailer->send($message);
            $task->setReminderDone(true);
        }

        $this->em->flush();

        return sizeof($tasks);
    }
}