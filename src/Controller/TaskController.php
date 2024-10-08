<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("{listingId}/task", name="task_", requirements={"listingId"="\d+"})
 */
class TaskController extends Controller
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @Route("/new", name="create")
     */
    public function create(Request $request, $listingId)
    {
        $listing = $this->em->getRepository(Listing::class)->find($listingId);
        $task = new Task();
        $task->setListing($listing);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($task);
            $this->em->flush();
            $this->addFlash('success', "Task added");
            return $this->redirectToRoute('listing_show', ['listingId' => $listingId]);
        }
        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
            'listing' => $listing
        ]);
    }

    /**
     * @Route("/{taskId}/edit", name="edit", requirements={"taskId"="\d+"}))
     */
    public function edit(Request $request, $listingId, $taskId)
    {
        $task = $this->em->getRepository(Task::class)->find($taskId);
        if(empty($task)){
            $this->addFlash('warning', "Task Empty, impossible to edit it");
            return $this->redirectToRoute('listing_show', ['listingId' => $listingId]);
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $name = $task->getName();
            $this->addFlash('success', "Task $name Edited");
            return $this->redirectToRoute('listing_show', ['listingId' => $listingId]);
        }
        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
            'listing' => $task->getListing()
        ]);
    }
}
