<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\DiscussionType;
use App\Form\TrickFormType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Services\PictureService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

#[Route('/trick')]
class TrickController extends AbstractController
{


    /**
     * @throws Exception
     */
    #[Route('/{slug}', name: 'trick_show')]
    public function show(?Trick $trick, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, Request $request, ?Discussion $discussions): Response
    {
        //phpinfo();
        if (!$trick) {
            return $this->redirectToRoute('app_home');
        }
        $manager = $doctrine->getManager();
        $image = $manager->getRepository(Image::class);
        $img = $image->findOneBy(['idTrick' => $trick->getId()]);

        $discussion = new Discussion();

        //Crée le formulaire
        $formDiscussion = $this->createForm(DiscussionType::class, $discussion);
        $formDiscussion->handleRequest($request);

        if ($formDiscussion->isSubmitted() && $formDiscussion->isValid()) {
            $discussions = $formDiscussion->get('content')->getData();
            $discussion->setIduser($this->getUser());
            $date = new DateTime('@' . strtotime('now'));

            $discussion->setCreationDate($date);
            $discussion->setTrick($trick);
            $entityManager->persist($discussion);
            $entityManager->flush();
        }
        $videos = $entityManager->getRepository(Video::class)->findBy(['idTrick' => $trick->getId()]);
        $images = $entityManager->getRepository(Image::class)->findBy(['idTrick' => $trick->getId()]);

        $discussions = $trick->getDiscussions();
        $criteria = Criteria::create()->setMaxResults(4);
        $limitedDiscussions = $discussions->matching($criteria);

        //dd($limitedDiscussions->isEmpty());

        $parameters = [
            'trick' => $trick,
            'image' => $img,
            'videos' => $videos,
            'images' => $images,
            //'discussion' => $trick->getDiscussions(),
            'discussionForm' => $formDiscussion->createView(),
            'user' => $this->getUser(),
            'limitedDiscussions' => $limitedDiscussions
        ];


        return $this->render('home/trick.html.twig', $parameters);
    }

    /**
     * @throws Exception
     */
//    #[Route('/{slug}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager, PictureService $pictureService, SessionInterface $session): Response
//    {
//        $form = $this->createForm(TrickType::class, $trick);
//        //$form = $this->createForm(TrickType::class, $trick, ['multipart' => true]);
//
//        $form->handleRequest($request);
//
//        $discussions = $trick->getDiscussions();
//        $criteria = Criteria::create()->setMaxResults(4);
//        $limitedDiscussions = $discussions->matching($criteria);
//
//
//        if ($form->isSubmitted() ) {
//            //dd($trick);
//            $trick->setIdUser($this->getUser());
//
//            // ajouter la sauvegarde des images
//
//            $images = $form->get('images')->getData();
//            $videos = $form->get('videos')->getData();
//            dd($videos);
//            $date = new DateTime('@'.strtotime('now'));
//
//            $trick->setModificationDate($date);
//            foreach ($images as $image) {
//
//                $folder = 'tricks';
//                $img = new Image();
//                $file = $pictureService->add($image, $folder, 300, 300);
//
//                $img->setImage($file);
//                $trick->addImage($img);
//            }
//            $videoObjects = [];
//            if (!empty($videos)) {
//
//                foreach ($videos as $videoUrl) {
//                    if (is_string($videoUrl->getVideo())) {
//                        $video = new Video();
//                        $video->setVideo($videoUrl->getVideo());
//                        $videoObjects[] = $video;
//                    }
//                }
//            }
//
//
//            // Set the videos to the trick entity
//            foreach ($videoObjects as $video) {
//                $trick->addVideo($video);
//            }
//
//
//            $entityManager->persist($trick);
//            $entityManager->flush();
//
//            $session->getFlashBag()->add('success', 'La figure à bien été modifié');
//            return $this->redirectToRoute('app_home', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('trick/edit.html.twig', [
//            'trick' => $trick,
//            'trickForm' => $form,
//            'limitedDiscussions' => $limitedDiscussions
//        ]);
//    }


//    #[Route('/{slug}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager, PictureService $pictureService, SessionInterface $session, ValidatorInterface $validator): Response
//    {
//        $form = $this->createForm(TrickType::class, $trick);
//        $form->handleRequest($request);
//
//        $discussions = $trick->getDiscussions();
//        $criteria = Criteria::create()->setMaxResults(4);
//        $limitedDiscussions = $discussions->matching($criteria);
//
//        //dd($validator->validate($form->getData()));
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $trick->setIdUser($this->getUser());
//
//            // Save images
//            $images = $form->get('images')->getData();
//            foreach ($images as $image) {
//                $folder = 'tricks';
//                $img = new Image();
//                $file = $pictureService->add($image, $folder, 300, 300);
//                $img->setImage($file);
//                $trick->addImage($img);
//            }
//
//            // Save videos
//            $videos = $form->get('videos')->getData();
//            //$trick->setVideos(new ArrayCollection()); // Clear existing videos
//
//            foreach ($videos as $video) {
//                if ($video instanceof Video) {
//                    $trick->addVideo($video);
//                }
//            }
//
//            // Set modification date
//            $date = new DateTime('@' . strtotime('now'));
//            $trick->setModificationDate($date);
//
//            $entityManager->persist($trick);
//            $entityManager->flush();
//
//            $session->getFlashBag()->add('success', 'La figure a bien été modifiée');
//            return $this->redirectToRoute('app_home', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
//        }else {
//            // Debugging: Output validation errors
//            $errors = $validator->validate($form->getData());
//            dump($errors);
//        }
//
//        return $this->render('trick/edit.html.twig', [
//            'trick' => $trick,
//            'trickForm' => $form,
//            'limitedDiscussions' => $limitedDiscussions
//        ]);
//    }


    #[Route('/{slug}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        $discussions = $trick->getDiscussions();
        $criteria = Criteria::create()->setMaxResults(4);
        $limitedDiscussions = $discussions->matching($criteria);

        if ($form->isSubmitted()) {
            $trick->setIdUser($this->getUser());

            // ajouter la sauvegarde des images

            $images = $form->get('images')->getData();
            $videos = $form->get('videos')->getData();
            $date = new DateTime('@'.strtotime('now'));

            $trick->setModificationDate($date);
            foreach ($images as $image) {

                $folder = 'tricks';
                $img = new Image();
                $file = $pictureService->add($image, $folder, 300, 300);

                $img->setImage($file);
                $trick->addImage($img);
            }


            foreach ($trick->getVideos() as $video) {
                $video->setIdTrick($trick);
            }


            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'trickForm' => $form,
            'limitedDiscussions' => $limitedDiscussions
        ]);
    }


    #[Route('/loadMoreDiscussions', name: 'loadMoreDiscussions', methods: ['GET'])]
    public function loadDiscussions(Request $request, Trick $trick): JsonResponse
    {
        try {
            $offset = $request->query->getInt('offset', 0);
            $limit = 4;

            $discussions = $trick->getDiscussions();
            $slicedDiscussions = $discussions->slice($offset, $limit);

            $jsonData = [];
            foreach ($slicedDiscussions as $discussion) {
                $discussionData = [
                    'idUser' => $discussion->getIduser()->getId(),
                    'content' => $discussion->getContent(),
                    // Add other properties as needed
                ];
                $jsonData[] = $discussionData;
            }

            return $this->json($jsonData);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{slug}/delete', name: 'app_trick_delete')]
    public function deleteTrick(Trick $trick = null, ManagerRegistry $doctrine): RedirectResponse
    {
        //Recuperer la personne.
        if ($trick) {
            $manager = $doctrine->getManager();
            $manager->remove($trick);
            $manager->flush();
            $this->addFlash('success', "le trick est bien supprimé");
        } else {
            $this->addFlash('erreur', "le trick est inexistant");
        }
        return $this->redirectToRoute('app_home');
    }


    #[Route('/trick/add', name: 'app_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, PictureService $pictureService, SessionInterface $session): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick,["validation_groups" => ["creation"]]);
        $form->handleRequest($request);
        $date = new DateTime('@'.strtotime('now'));

        $manager = $doctrine->getManager();
        $tricks = $manager->getRepository(Trick::class);
        $trickSimilairetrouve = $tricks->findOneBy(['slug' => $trick->getSlug()]);
        if($trickSimilairetrouve != null){
            $session->getFlashBag()->add('danger', 'Le nom de la figure saisie est déjà existant');
            return $this->render('trick/new.html.twig', [
                'trick' => $trick,
                'form' => $form->createView(),
            ]);
        }
        try {

            // perform some task
            //dd($form->get('videos')->getData());
            if ($form->isSubmitted() && $form->isValid()) {
                $images = $form->get('images')->getData();
                $videos = $form->get('videos')->getData();


                foreach ($trick->getVideos() as $video) {
                    $video->setIdTrick($trick);
                }


                $trick->setCreationDate($date);
                $trick->setIdUser($this->getUser());
                foreach ($images as $image) {

                    $folder = 'tricks';
                    $img = new Image();
                    $file = $pictureService->add($image, $folder, 300, 300);

                    $img->setImage($file);
                    $trick->addImage($img);
                }

                $entityManager->persist($trick);
                $entityManager->flush();

                $session->getFlashBag()->add('success', 'La figure à bien été ajouté');
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);

            }

        } catch (\Exception $e) {
            dd($e);
        }


        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trick/{slug}', name: 'app_discussion')]
    public function discussionTrick(): Response
    {
        //Crée un "nouveau trick"
        $discussion = new Discussion();

        //Crée le formulaire
        $form = $this->createForm(DiscussionType::class, $discussion);

        return $this->render('trick/discussion.html.twig', [
            'discussionForm' => $form->createView()
        ]);

    }

    #[Route('/trick/{slug}/edit/{id}/deleteImage', name: 'delete_image', methods: ['POST'])]
    public function deleteImage(Request $request, string $slug, int $id, ManagerRegistry $doctrine): Response
    {
        $submittedToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete' . $id, $submittedToken)) {
            $entityManager = $doctrine->getManager();
            $image = $entityManager->getRepository(Image::class)->find($id);

            if (!$image) {
                $this->addFlash('error', 'Image not found.');
                return $this->redirectToRoute('app_home');
            }

            $entityManager->remove($image);
            $entityManager->flush();

            $this->addFlash('success', 'Image deleted successfully.');
            return $this->redirectToRoute('app_home');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_home');
        }
    }



    // essaie loadmore discussion
    #[Route('/trick/{slug}/loadMoreComments', name: 'loadMoreComments', methods: ['GET'])]
    public function loadMoreComments(Request $request, Trick $trick): JsonResponse
    {
        $offset = $request->query->getInt('offset', 0);
        $limit = 4; // Adjust the limit as needed

        $comments = $trick->getDiscussions()->slice($offset, $limit);

        $jsonData = [];
        foreach ($comments as $comment) {
            $commentData = [
                'id' => $comment->getId(),
                'content' => $comment->getContent(),
                // Add other properties as needed
            ];
            $jsonData[] = $commentData;
        }

        return $this->json($jsonData);
    }



}


