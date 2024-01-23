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
        //dd($tricks[2]->getImages()->get(0) == null);
        //dd($tricks[2]->getImages() == null);
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
            'user' => $this->getUser()
        ]);
    }





}
