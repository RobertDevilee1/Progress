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
use App\Repository\TrophyRepository;
use App\Entity\Trophy;





class TrainingSessionController extends AbstractController
{
    #[Route('/trainingen', name: 'app_training_list')]
    public function trainingList(TrainingRepository $trainingRepository): Response
    {
        $trainings = $trainingRepository->findAll();

        return $this->render('training/list.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    #[Route('/training/new/{id}', name: 'app_training_fill')]
    public function fillTraining(Request $request, Training $training, EntityManagerInterface $em, TrophyRepository $trophyRepo): Response {
        $session = new TrainingSession();
        $session->setTraining($training);
        $session->setUser($this->getUser());

        $form = $this->createForm(TrainingSessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($session);
            $em->flush();

            if ($training->getName() === 'bench press' && $session->getWeight() >= 100 && !$trophyRepo->userHasTrophy($this->getUser(), '100kg Benched')) {
                $trophy = new Trophy();
                $trophy->setName('100kg Benched');
                $trophy->setDescription('Je hebt 100kg of meer gebenchd!');
                $trophy->setUser($this->getUser());
                $trophy->setTrainingSession($session);
                $trophy->setImage('trophies/tropfy.png');
                $em->persist($trophy);
                $this->addFlash('success', '🏆 Nieuwe trofee: 100kg Benched!');

            }



            if ($training->getName() === 'pull down' && $session->getWeight() >= 60 && !$trophyRepo->userHasTrophy($this->getUser(), 'Rug van Staal')) {
                $trophy = new Trophy();
                $trophy->setName('Rug van Staal');
                $trophy->setDescription('Je hebt 60kg getrokken met een pull down! Wat een kracht in je rug.');
                $trophy->setUser($this->getUser());
                $trophy->setTrainingSession($session);
                $trophy->setImage('trophies/tropfy.png');
                $em->persist($trophy);
                $this->addFlash('success', '🏆 Nieuwe trofee: 60kg pull down!');

            }



            if ($training->getName() === 'bicep curl' && $session->getWeight() >= 30 && !$trophyRepo->userHasTrophy($this->getUser(), 'Bicep Bom')) {
                $trophy = new Trophy();
                $trophy->setName('Bicep Bom');
                $trophy->setDescription('Je hebt 30kg of meer gekurld. Tijd voor mouwscheuren!');
                $trophy->setUser($this->getUser());
                $trophy->setTrainingSession($session);
                $trophy->setImage('trophies/tropfy.png');
                $em->persist($trophy);
                $this->addFlash('success', '🏆 Nieuwe trofee: 30kg curl!');

            }



            if ($training->getName() === 'Leg press' && $session->getWeight() >= 200 && !$trophyRepo->userHasTrophy($this->getUser(), 'Legday Overlord')) {
                $trophy = new Trophy();
                $trophy->setName('Legday Overlord');
                $trophy->setDescription('Je hebt 200kg geperst met je benen. Niemand slaat legday over zoals jij.');
                $trophy->setUser($this->getUser());
                $trophy->setTrainingSession($session);
                $trophy->setImage('trophies/tropfy.png');
                $em->persist($trophy);
                $this->addFlash('success', '🏆 Nieuwe trofee: 200kg leg press!');

            }



            if ($training->getName() === 'calf lift' && $session->getWeight() >= 100 && !$trophyRepo->userHasTrophy($this->getUser(), 'KuitenKoning')) {
                $trophy = new Trophy();
                $trophy->setName('KuitenKoning');
                $trophy->setDescription('Je kuiten hebben 100kg getild! Straks loop je op wolken.');
                $trophy->setUser($this->getUser());
                $trophy->setTrainingSession($session);
                $trophy->setImage('trophies/tropfy.png');
                $em->persist($trophy);
                $this->addFlash('success', '🏆 Nieuwe trofee: 100kg kuiten!');

            }



            if ($training->getName() === 'flying exersize' && $session->getWeight() >= 20 && !$trophyRepo->userHasTrophy($this->getUser(), 'Schoudervleugels')) {
                $trophy = new Trophy();
                $trophy->setName('Schoudervleugels');
                $trophy->setDescription('Je hebt 20kg per arm gedaan bij de fly oefening – vlieggewicht bereikt!');
                $trophy->setUser($this->getUser());
                $trophy->setTrainingSession($session);
                $trophy->setImage('trophies/tropfy.png');
                $em->persist($trophy);
                $this->addFlash('success', '🏆 Nieuwe trofee: 20kg flying exersize!');

            }



            if ($training->getName() === 'tricep push' && $session->getWeight() >= 40 && !$trophyRepo->userHasTrophy($this->getUser(), 'Tricep Titan')) {
                $trophy = new Trophy();
                $trophy->setName('Tricep Titan');
                $trophy->setDescription('Je hebt 40kg of meer gepusht met je triceps. Beest!');
                $trophy->setUser($this->getUser());
                $trophy->setTrainingSession($session);
                $trophy->setImage('trophies/tropfy.png');
                $em->persist($trophy);
                $this->addFlash('success', '🏆 Nieuwe trofee: 40kg push push!');

            }

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

    #[Route('/training/save-session', name: 'training_save_session', methods: ['POST'])]
    public function saveSession(Request $request, EntityManagerInterface $em): Response
    {
        $date = new \DateTime($request->request->get('date'));
        $user = $this->getUser();

        $existing = $em->getRepository(TrainingSession::class)->findOneBy([
            'user' => $user,
            'date' => $date
        ]);

        $session = $existing ?: new TrainingSession();
        $session->setUser($user);
        $session->setDate($date);
        $session->setPlannedDate($request->request->getBoolean('planned') ? $date : null);
        $session->setWasPresent($request->request->getBoolean('done'));

        $em->persist($session);
        $em->flush();

        return $this->redirectToRoute('training_calendar');
    }




}

