<?php

namespace App\Controller;

use App\Entity\Listing;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/listing")
 */
class ListingController extends Controller
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    /**
     * @Route("/", name="listing_all")
     */
    public function list()
    {
        $listings = $this->em->getRepository(Listing::class)->findAll();
        return $this->render('listing/index.html.twig', [
            'listing' => $listings
        ]);
    }

    /**
     * @Route("/create", name="listing_create", methods="POST")
     */
    public function create(Request $request)
    {
        $name = $request->get('name');
        if(empty($name)){
            $this->addFlash('warning', "List name is empty");
            return $this->redirectToRoute('listing_all');
        }
        $listing = new Listing();
        $listing->setName($name);
        try {
            $this->em->persist($listing);
            $this->em->flush();
            $this->addFlash('success', "List added");
        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash('warning', "$name already exist");
        }
     
        return $this->redirectToRoute('listing_all');
    }
}
