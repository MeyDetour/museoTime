<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\MuseumImage;
use App\Form\ImageType;
use App\Form\MuseumImageType;
use App\Repository\MuseumImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MuseumImageController extends AbstractController
{
    #[Route('/images/show', name: 'get_museums_images')]
    public function getMuseumsImages(MuseumImageRepository $museumImageRepository): Response
    {
        $museumsImages = $museumImageRepository->findAll();

        return $this->render("museum_image/images.html.twig", [
            "museumsImages" => $museumsImages
        ]);
    }

    #[Route('/museum/image', name: 'app_museum_image')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {


        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);
        $formImage->handleRequest($request);
        if ($formImage->isSubmitted()) {

            $manager->persist($image);
            $manager->flush();

            $museumImage = new MuseumImage();
            $museumImage->setMuseumId($request->get("museumID"));
            $museumImage->setImage($image);

            $manager->persist($museumImage);
            $manager->flush();



            $image->setMuseumImage($museumImage);
            $manager->persist($image);
            $manager->flush();


            return $this->redirectToRoute('app_museum_image');
        }

        return $this->render('museum_image/index.html.twig', [
            "form" => $formImage->createView()
        ]);
    }
}
