<?php
namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class TrainingSessionFormTest extends WebTestCase
{
    public function testTrainingFormSubmit()
    {
        $client = static::createClient();
        $em = static::getContainer()->get('doctrine')->getManager();

        $user = $em->getRepository(User::class)->findOneBy(['email' => 'robertdevilee@outlook.com']);

        if (!$user) {
            $this->fail('User Robert Devilee not found in the database.');
        }

        $client->loginUser($user);
        $crawler = $client->request('GET', '/training/new/1');

        $form = $crawler->selectButton('Opslaan')->form([
            'training_session[sets]' => 4,
            'training_session[reps]' => 8,
            'training_session[weight]' => 90,
            'training_session[date]' => (new \DateTime())->format('Y-m-d H:i'),
        ]);

        $client->submit($form);

        $this->assertResponseRedirects();
    }
}




