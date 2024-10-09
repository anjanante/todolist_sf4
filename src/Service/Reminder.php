<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class Reminder
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    
    public function remind()
    {
        $tasks = $this->em->getRepository(Task::class)->findAllToRemind(new \DateTime());        
        
        return sizeof($tasks);
    }
}