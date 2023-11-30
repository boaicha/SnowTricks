<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use App\Services\PictureService;
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
        //dd($tricks[0]->getImages()->get(0));
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
            'user' => $this->getUser()
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        $product = new Trick();
        $form = $this->createForm(TrickType::class, $product);
        $form->handleRequest($request);
        $date = new DateTime('@'.strtotime('now'));

        if ($form->isSubmitted() && $form->isValid()) {
            // recuperer les images
            $images =$form->get('images')->getData();

            foreach($images as $image){
                // définir le dossier de destination
                $folder = 'tricks';
                $img = new Image();
                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);


                $img->setImage($fichier);
                $product->addImage($img);
                $product->setIdUser($this->getUser());
            }


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
