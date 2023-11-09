<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findAll();
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Trick();
        $form = $this->createForm(TrickType::class, $product);
        $form->handleRequest($request);
        $date = new DateTime('@'.strtotime('now'));

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreationDate($date);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

}
