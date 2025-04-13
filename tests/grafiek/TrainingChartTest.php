<?php


namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrainingChartTest extends WebTestCase
{
    public function testChartPageLoadsForTraining()
    {
        $client = static::createClient();
        $client->loginUser($this->getExistingUser());

        $client->request('GET', '/training/1/chart');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('canvas#trainingChart');
    }

    private function getExistingUser(): User
    {
        $container = static::getContainer();
        $userRepo = $container->get('doctrine')->getRepository(User::class);

        $user = $userRepo->findOneBy(['email' => 'robertdevilee@outlook.com']);

        if (!$user) {
            throw new \Exception('Gebruiker Robert Devilee niet gevonden in de testdatabase.');
        }

        return $user;
    }

}
