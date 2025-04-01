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

class TrainingSessionController extends AbstractController
{
    #[Route('/training/new', name: 'app_training_new')]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $session = new TrainingSession();
        $session->setUser($this->getUser());

        $form = $this->createForm(TrainingSessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($session);
            $em->flush();
            return $this->redirectToRoute('api_trainings');
        }

        return $this->render('training/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/api/trainings', name: 'api_trainings')]
    public function getTrainingData(TrainingSessionRepository $repo): JsonResponse
    {
        $sessions = $repo->findBy(['user' => $this->getUser()]);
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

