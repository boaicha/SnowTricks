<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Repository\TrickRepository;
use App\Services\PictureService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;
use function PHPUnit\Framework\isEmpty;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findby([],['creationDate' => 'DESC'], 6, 0);
        //dd($tricks[0]->getImages()->get(0));
        //dd(isEmpty($tricks[0]->getImages()) == null);
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
            'user' => $this->getUser()
        ]);
    }

    #[Route('/{offset}', name: 'loadmore', methods: ['GET', 'POST'])]
    public function loadmore(TrickRepository $trickRepository, $offset = 6): Response
    {
        $tricks = $trickRepository->findby([],['creationDate' => 'DESC'], 6, $offset);

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
            $videos = $form->get('videos')->getData();

            $video = new Video();

            $video->setVideo($videos);
            $product->addVideo($video);
            $product->setIdUser($this->getUser());
            foreach($images as $image){
                // définir le dossier de destination
                $folder = 'tricks';
                $img = new Image();
                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);


                $img->setImage($fichier);
                $product->addImage($img);

            }


            $product->setCreationDate($date);
            $entityManager->persist($product);
            $entityManager->flush();

            //dd($videos);
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/new.html.twig', [
            'product' => $product,
            'form' => $form,

        ]);
    }

}
