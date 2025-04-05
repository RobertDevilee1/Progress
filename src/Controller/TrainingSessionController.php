<?php

namespace App\Controller;

use App\Entity\TrainingSession;
use App\Form\TrainingSessionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TrainingSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Training;
use App\Repository\TrainingRepository;
use App\Service\TrophyService;



class TrainingSessionController extends AbstractController
{
    #[Route('/trainings', name: 'app_training_list')]
    public function trainingList(TrainingRepository $trainingRepository): Response
    {
        $trainings = $trainingRepository->findAll();

        return $this->render('training/list.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    #[Route('/training/new/{id}', name: 'app_training_fill')]
    public function fillTraining(Request $request, Training $training, EntityManagerInterface $em, TrophyRepository $trophyRepo): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($session);
            $em->flush();

            // 🏆 Trofee bij 100kg of meer
            if ($session->getWeight() >= 100 && !$trophyRepo->userHasTrophy($this->getUser(), '100kg Benched')) {
                $trophy = new Trophy();
                $trophy->setName('100kg Benched');
                $trophy->setDescription('Je hebt 100kg of meer gebenchd!');
                $trophy->setUser($this->getUser());
                $trophy->setTrainingSession($session);
                $trophy->setImage('trophies/bench_100kg.png'); // zorg dat je deze afbeelding hebt
                $em->persist($trophy);
                $em->flush();
            }

            return $this->redirectToRoute('app_training_chart_by_id', ['id' => $training->getId()]);
        }
    }

    #[Route('/training/{id}/chart', name: 'app_training_chart_by_id')]
    public function trainingChartById(Training $training, TrainingSessionRepository $repo): Response
    {
        // De twig haalt zijn data met JS op via API, dus alleen training meegeven
        return $this->render('training_session/stats.html.twig', [
            'training' => $training
        ]);
    }

    #[Route('/api/training/{id}/sessions', name: 'api_training_sessions_by_id')]
    public function getTrainingSessionsByTraining(Training $training, TrainingSessionRepository $repo): JsonResponse
    {
        $sessions = $repo->findBy([
            'user' => $this->getUser(),
            'training' => $training
        ]);

        $data = [];

        foreach ($sessions as $session) {
            $data[] = [
                'date' => $session->getDate()->format('Y-m-d'),
                'weight' => $session->getWeight(),
                'sets' => $session->getSets(),
                'reps' => $session->getReps(),
            ];
        }

        return new JsonResponse($data);
    }


}

