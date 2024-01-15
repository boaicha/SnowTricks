<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Monolog\DateTimeImmutable;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Image;
use App\Entity\Video;
use App\Entity\Discussion;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Load data in database
     * @param ObjectManager $manager object manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // CATEGORIES
        $categories = [];
        $catDatas = [
            [
                'name' => 'Grab',
                'description' => 'Un grab consiste à attraper la planche avec la main pendant le saut'
            ],
            [
                'name' => 'Rotation',
                'description' => 'On désigne par le mot « rotation » uniquement des rotations horizontales'
            ],
            [
                'name' => 'Flip',
                'description' => 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les
                    back flips, rotations en arrière.'
            ],
            [
                'name' => 'Slide',
                'description' => 'Un slide consiste à glisser sur une barre de slide.'
            ],
            [
                'name' => 'One foot',
                'description' => 'Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante
                    pour mettre en évidence le fait que le pied n\'est pas fixé.'
            ],
            [
                'name' => 'Old school',
                'description' => 'Le terme old school désigne un style de freestyle caractérisée par en ensemble de figure et
                    une manière de réaliser des figures passée de mode'
            ],
            [
                'name' => 'Autres',
                'description' => ''
            ]
        ];

        foreach ($catDatas as $data) {
            $category = new Category();
            $category->setName($data['name']);
            $category->setDescription($data['description']);

            $categories[$data['name']] = $category;
            $manager->persist($category);
        }

        // USERS
        $users = [];
        $usersData = [
            ['name' => 'simon', 'email' => 'simoncharbonnier03@gmail.com', 'profil_photo' => '1.jpg'],
            ['name' => 'john', 'email' => 'johndoe@gmail.com'],
            ['name' => 'shaun', 'email' => 'shaunwinter@gmail.com', 'profil_photo' => '3.jpg'],
            ['name' => 'martina', 'email' => 'martinasnow@gmail.com', 'profil_photo' => '4.jpg']
        ];

        foreach ($usersData as $data) {
            $user = new User();
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword($this->hasher->hashPassword($user, 'secret'));
            $user->setIsVerified(true);
            if (isset($data['avatar'])) {
                $user->setProfilPhoto($data['avatar']);
            }

            $users[] = $user;
            $manager->persist($user);
        }

        // TRICKS
        $tricks = [];
        $tricksData = [
            [
                'name' => 'Ollie',
                'description' => 'Comme en skateboard, le ollie consiste à faire décoller votre planche du sol, pour effectuer
                    un petit saut.Le but est d\'utiliser le flex de la board pour décoller au dessus du sol.',
                'idCategory' => $categories['Autres'],
                'images' => [
                    ['name' => 'ollie-0.jpg'],
                    ['name' => 'ollie-1.jpg']
                ],
                'videos' => [
                    ['name' => 'aAefkzI-zS0']
                ]
            ],
            [
                'name' => 'Nollie',
                'description' => 'Accroupis-toi, déplace ton poids vers l\'avant, puis utilise le nez de ta planche pour sauter.',
                'idCategory' => $categories['Autres'],
                'images' => [
                    ['name' => 'nollie-0.jpg']
                ],
                'videos' => [
                    ['name' => 'aAzP3wNT220']
                ]
            ],
            [
                'name' => 'Tail Press',
                'description' => 'Le Tail Press est initié en déplaçant ton poids vers l\'arrière de ta planche tout en restant
                    droit et en soulevant le Nose de la neige.',
                'idCategory' => $categories['Autres'],
                'images' => [
                    ['name' => 'tail-press-0.jpg']
                ],
                'videos' => [
                    ['name' => 'Kv0Ah4Xd8d0']
                ]
            ],
            [
                'name' => 'Nose Press',
                'description' => 'C\'est l\’opposé du Tail Press. Le Nose Press exige que ton poids soit sur l\'avant
                    de la planche, avec l\'arrière décollé de la neige.',
                'idCategory' => $categories['Autres'],
                'images' => [
                    ['name' => 'nose-press-0.jpg']
                ],
                'videos' => [
                    ['name' => 'Px2YuKQVS_g']
                ]
            ],
            [
                'name' => 'Tripod',
                'description' => 'Va en ligne droite et regarde derrière toi.',
                'idCategory' => $categories['Autres'],
                'images' => [
                    ['name' => 'tripod-0.jpg']
                ],
                'videos' => [
                    ['name' => 'P6crQSwDjJY']
                ]
            ],
            [
                'name' => 'Indy',
                'description' => 'Attrape le carre des orteils de ta planche, entre les fixations, avec ta main arrière.',
                'idCategory' => $categories['Grab'],
                'images' => [
                    ['name' => 'indy-0.jpg'],
                    ['name' => 'indy-1.jpg']
                ],
                'videos' => [
                    ['name' => '6yA3XqjTh_w']
                ]
            ],
            [
                'name' => 'Stalefish',
                'description' => 'Passe la main derrière ton genou arrière et attrape le carre de ta planche entre les fixations,
                    côté talon, avec ta main arrière.',
                'idCategory' => $categories['Grab'],
                'images' => [
                    ['name' => 'stalefish-0.jpg']
                ],
                'videos' => [
                    ['name' => 'f9FjhCt_w2U']
                ]
            ],
            [
                'name' => 'Tail',
                'description' => 'Attrape le talon de ta planche avec ta main arrière (juste à l\'extrémité, pas sur les côtés).',
                'idCategory' => $categories['Grab'],
                'images' => [
                    ['name' => 'tail-0.jpg']
                ]
            ],
            [
                'name' => 'Weddle',
                'description' => 'Du nom de Chris Weddle, l\'inventeur, attrape le carre des orteils entre les fixations avec ta main avant.',
                'idCategory' => $categories['Grab'],
                'images' => [
                    ['name' => 'weddle-0.jpg']
                ],
                'videos' => [
                    ['name' => 'c1vfTvXjVxY']
                ]
            ],
            [
                'name' => 'Melon',
                'description' => 'Passe la main avant derrière ton genou et attrape le bord des talons entre les fixations.',
                'idCategory' => $categories['Grab'],
                'images' => [
                    ['name' => 'melon-0.jpg']
                ],
                'videos' => [
                    ['name' => 'OMxJRz06Ujc']
                ]
            ],
            [
                'name' => 'Method',
                'description' => 'À partir de la prise du Melon, étends tes jambes de façon à ce que ton corps ait presque la
                    forme de la queue d\'un scorpion',
                'idCategory' => $categories['Grab'],
                'images' => [
                    ['name' => 'method-0.jpg']
                ],
                'videos' => [
                    ['name' => 'lunYxCQrs1E']
                ]
            ],
            [
                'name' => 'Nose',
                'description' => 'Attrape l\'extrémité avant de ta planche avec ta main avant.',
                'idCategory' => $categories['Grab'],
                'images' => [
                    ['name' => 'nose-0.jpg']
                ],
                'videos' => [
                    ['name' => 'gZFWW4Vus-Q']
                ]
            ],
            [
                'name' => 'Backflip',
                'description' => 'Un Backflip fait tourner la planche perpendiculairement à la neige, tu fais donc un Flip
                    directement en arrière',
                'idCategory' => $categories['Flip'],
                'images' => [
                    ['name' => 'backflip-0.jpg']
                ],
                'videos' => [
                    ['name' => 'arzLq-47QFA'],
                    ['name' => '_8TBfD5VPnM'],
                ]
            ],
            [
                'name' => 'Frontflip',
                'description' => 'Tout comme le Tamedog, le Frontflip te demande de faire un Nose-Press et un Nollie sur un bord.',
                'idCategory' => $categories['Flip'],
                'images' => [
                    ['name' => 'frontflip-0.jpg']
                ],
                'videos' => [
                    ['name' => 'BVeAbNIHktE']
                ]
            ],
            [
                'name' => 'Rodéo',
                'description' => 'Un Rodéo est un Frontflip avec un twist. Littéralement. Lorsque tu arrives sur le rebord
                    du saut, déclenche un virage Frontside.',
                'idCategory' => $categories['Flip'],
                'videos' => [
                    ['name' => 'vf9Z05XY79A']
                ]
            ],
            [
                'name' => 'Backside Rodéo',
                'description' => 'L\'inverse du Rodéo, le Backside Rodéo consiste à amorcer un virage Backside à partir du saut',
                'idCategory' => $categories['Flip'],
                'images' => [
                    ['name' => 'backside-rodeo-0.jpg']
                ],
                'videos' => [
                    ['name' => 'QX6yvs6uTVg']
                ]
            ],
            [
                'name' => 'Bluntslide',
                'description' => 'Un Bluntslide signifie que le rail se trouve sous l\'une de tes fixations au lieu d\'être au
                    milieu de la planche.',
                'idCategory' => $categories['Slide'],
                'images' => [
                    ['name' => 'bluntslide-0.jpg'],
                    ['name' => 'bluntslide-1.jpg']
                ],
                'videos' => [
                    ['name' => 'Nkotow1RyaQ']
                ]
            ],
        ];

        foreach ($tricksData as $data) {
            $user = $users[array_rand($users)];

            $trick = new Trick();
            $trick->setName($data['name']);
            $trick->setDescription($data['description']);
            $trick->setIdUser($user);
            $trick->setIdCategory($data['idCategory']);
            $trick->setCreationDate(new DateTimeImmutable('now'));
            $trick->setModificationDate(new DateTimeImmutable('now'));

            $tricks[] = $trick;
            $manager->persist($trick);

            if (isset($data['images'])) {
                foreach ($data['images'] as $data) {
                    $image = new Image();
                    $image->setImage($data['name']);
                    $image->setIdTrick($trick);

                    $manager->persist($image);
                }
            }

            if (isset($data['videos'])) {
                foreach ($data['videos'] as $data) {
                    $video = new Video();
                    $video->setVideo($data['name']);
                    $video->setIdTrick($trick);

                    $manager->persist($video);
                }
            }
        }

        // COMMENTS
        $commentsData = [
            [
                'content' => 'J\'adore cette figure !'
            ],
            [
                'content' => 'Impossible ! C\'est si difficile à réaliser..'
            ],
            [
                'content' => 'C\'est clairement la plus stylée de toutes !'
            ],
            [
                'content' => 'Shaun a réussi à l\'entraînement !!!'
            ],
            [
                'content' => 'Tu es certain que la figure se fait de cette manière ?'
            ],
            [
                'content' => 'C\'est un classique !'
            ],
            [
                'content' => 'Et surtout bien penser à l\'atterrissage.. Sinon c\'est la blessure assurée'
            ],
            [
                'content' => 'J\'ai réussi à le faire grâce à la vidéo, très intéressant'
            ]
        ];

        foreach ($commentsData as $data) {
            $user = $users[array_rand($users)];
            $trick = $tricks[array_rand($tricks)];

            $comment = new Discussion();
            $comment->setIduser($user);
            $comment->setTrick($trick);
            $comment->setContent($data['content']);
            $comment->setCreationDate(new DateTimeImmutable('now'));

            $manager->persist($comment);
        }
        $manager->flush();
    }
}