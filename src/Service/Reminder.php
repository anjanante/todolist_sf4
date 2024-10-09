<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class Reminder
{
    private $em;
    private $template;
    private $mailer;

    public function __construct(EntityManagerInterface $em, \Twig_Environment $template, \Swift_Mailer $mailer){
        $this->em = $em;
        $this->template = $template;
        $this->mailer = $mailer;
    }
    
    public function remind()
    {
        $tasks = $this->em->getRepository(Task::class)->findAllToRemind(new \DateTime());        
        
        foreach ($tasks as $task) {
           $message = (new \Swift_Message("A task must be realised"))
                ->setFrom("nantedevy@todolistSf.com")
                ->setTo("nantedevy@gmail.com")
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