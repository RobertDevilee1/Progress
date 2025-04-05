<?php

namespace App\Controller;

use App\Entity\TrainingSession;
use App\Repository\TrainingSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankController extends AbstractController
{
    #[Route('/rank', name: 'app_rank_index')]
    public function index(TrainingSessionRepository $sessionRepo): Response
    {
        $user = $this->getUser();
        $sessions = $sessionRepo->findBy(['user' => $user]);

        $sessionsWithRanks = [];

        foreach ($sessions as $session) {
            $rank = $this->calculateRank($session->getWeight(), $user->getBodyWeight());
            $sessionsWithRanks[] = [
                'session' => $session,
                'rank' => $rank
            ];
        }

        return $this->render('rank/index.html.twig', [
            'sessionsWithRanks' => $sessionsWithRanks
        ]);
    }

    private function calculateRank(float $liftedWeight, float $bodyWeight): string
    {
        $ratio = $liftedWeight / $bodyWeight;

        return match (true) {
            $ratio >= 2.0 => 'Diamant',
            $ratio >= 1.6 => 'Platina',
            $ratio >= 1.2 => 'Goud',
            $ratio >= 0.8 => 'Zilver',
            default => 'Brons',
        };
    }
}

