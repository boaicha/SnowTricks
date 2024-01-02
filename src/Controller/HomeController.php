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
use Symfony\Component\HttpFoundation\JsonResponse;
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

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
            'user' => $this->getUser()
        ]);
    }

    // loadmore tricks à chaque clique
    #[Route('/loadMoreTrick', name: 'loadMoreTrick')]
    public function loadMoreTrick(Request $request, TrickRepository $trickRepository): JsonResponse
    {
        $offset = $request->query->getInt('offset', 0);
        $limit = 6; // Nombre de tricks à charger à chaque fois

        $tricks = $trickRepository->findby([], ['creationDate' => 'DESC'], $limit, $offset);

        $jsonData = [];
        foreach ($tricks as $trick) {
            $trickData = [
                'id' => $trick->getId(),
                'description' => $trick->getDescription(),
                'slug' => $trick->getSlug()
            ];



            // Ajoutez des images si nécessaire
            $images = [];
            foreach ($trick->getImages() as $image) {
                $images[] = [
                    'image' => $image->getImage(),
                    // Ajoutez d'autres propriétés de l'image si nécessaire
                ];
            }
            $trickData['images'] = $images;

            // Ajoutez d'autres détails du trick si nécessaire
            // $trickData['autre_champ'] = $trick->getAutreChamp();

            $jsonData[] = $trickData;
        }

        //dd($jsonData);

        return new JsonResponse($jsonData);
    }





}
