<?php

// tests/Form/TrainingSessionFormTest.php
namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class TrainingSessionFormTest extends WebTestCase
{
    public function testTrainingFormSubmit()
    {
        $client = static::createClient();
        $em = static::getContainer()->get('doctrine')->getManager();

        // Zoek de gebruiker met de e-mail 'robertdevilee@outlook.com'
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'robertdevilee@outlook.com']);

        // Als de gebruiker niet gevonden wordt, kunnen we een foutmelding geven
        if (!$user) {
            $this->fail('User Robert Devilee not found in the database.');
        }

        $client->loginUser($user); // Inloggen met de gevonden gebruiker
        $crawler = $client->request('GET', '/training/new/1'); // Stel hier de juiste training-id in

        // Vul het formulier in voor een nieuwe trainingssessie
        $form = $crawler->selectButton('Opslaan')->form([
            'training_session[sets]' => 4,
            'training_session[reps]' => 8,
            'training_session[weight]' => 90,
            'training_session[date]' => (new \DateTime())->format('Y-m-d H:i'),
        ]);

        // Verstuur het formulier
        $client->submit($form);

        // Controleer of we worden doorgestuurd na het indienen van het formulier
        $this->assertResponseRedirects(); // Veronderstel dat we worden doorgestuurd naar de juiste pagina
    }
}




