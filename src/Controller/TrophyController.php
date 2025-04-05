<?php

namespace App\Controller;

use App\Repository\TrophyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class TrophyController extends AbstractController
{
    #[Route('/trophies', name: 'app_trophies')]
    public function show(EntityManagerInterface $em): Response
    {
        $trophies = $em->getRepository(Trophy::class)->findBy(['user' => $this->getUser()]);
        return $this->render('trophy/index.html.twig', [
            'trophies' => $trophies,
        ]);
    }

}
