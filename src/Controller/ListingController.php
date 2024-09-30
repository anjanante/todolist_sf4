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
     * @Route("/{listingId}", name="listing_show", requirements={"listingId"="\d+"})
     */
    public function show($listingId = null)
    {
        $listings = $this->em->getRepository(Listing::class)->findAll();

        if (!empty($listingId)) {
            $currentListing = $this->em->getRepository(Listing::class)->find($listingId);
        }

        if (empty($currentListing)) {
            $currentListing = current($listings);
        }

        return $this->render("listing/index.html.twig", ['listings' => $listings, 'currentListing' => $currentListing]);
    }

    /**
     * @Route("/create", name="listing_create", methods="POST")
     */
    public function create(Request $request)
    {
        $name = $request->get('name');
        if(empty($name)){
            $this->addFlash('warning', "List name is empty");
            return $this->redirectToRoute('listing_show');
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
     
        return $this->redirectToRoute('listing_show');
    }

    /**
     * @Route("/{listingId}/delete", name="listing_delete", requirements={"listingId"="\d+"})
     */
    public function delete($listingId)
    {
        $listing = $this->em->getRepository(Listing::class)->find($listingId);

        if (empty($listing)) {
            $this->addFlash(
                "warning",
                "Unable to delete list"
            );
        } else {
            $this->em->remove($listing);
            $this->em->flush();

            $name = $listing->getName();

            $this->addFlash(
                "success",
                "The list « $name » has been deleted"
            );
        }

        return $this->redirectToRoute('listing_show');
    }
}
