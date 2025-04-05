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
    public function fillTraining(Request $request, Training $training, EntityManagerInterface $em): Response
    {
        $session = new TrainingSession();
        $session->setUser($this->getUser());
        $session->setTraining($training); // zet training vooraf

        $form = $this->createForm(TrainingSessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($session);
            $em->flush();

            return $this->redirectToRoute('app_training_chart_by_id', ['id' => $training->getId()]);
        }

        return $this->render('training/new.html.twig', [
            'form' => $form->createView(),
            'training' => $training,
        ]);
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

