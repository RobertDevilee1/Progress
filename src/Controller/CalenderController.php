<?php

namespace App\Controller;

use App\Repository\TrainingSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CalenderController extends AbstractController
{
    #[Route('/training/calendar', name: 'training_calendar')]
    public function calendar(TrainingSessionRepository $repo): Response
    {
        $sessions = $repo->findBy(['user' => $this->getUser()]);
        return $this->render('calender/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

}
