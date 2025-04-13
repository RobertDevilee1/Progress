<?php
namespace App\Tests\Trophy;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\TrainingSession;
use App\Entity\Trophy;

class TrainingTrophyTest extends WebTestCase
{
    public function testTrophyAwardedForMilestone()
    {
        $client = static::createClient();


        $em = static::getContainer()->get('doctrine')->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'robertdevilee@outlook.com']);


        $client->loginUser($user);


        $crawler = $client->request('GET', '/training/new/1');
        $form = $crawler->selectButton('Opslaan')->form([
            'training_session[sets]' => 4,
            'training_session[reps]' => 10,
            'training_session[weight]' => 100,
            'training_session[date]' => (new \DateTime())->format('Y-m-d H:i'),
        ]);

        $client->submit($form);

        $user = $em->getRepository(User::class)->findOneBy(['email' => 'robertdevilee@outlook.com']);

        $trophy = $em->getRepository(Trophy::class)->findOneBy(['user' => $user]);

        $this->assertNotNull($trophy, "De gebruiker heeft geen trofee ontvangen na het bereiken van de mijlpaal.");

        $this->assertEquals('Rug van Staal', $trophy->getName(), "De naam van de trofee komt niet overeen met de verwachte naam.");
    }
}

