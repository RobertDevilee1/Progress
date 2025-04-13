<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class CalendarViewTest extends WebTestCase
{
    public function testCalendarPageLoadsAndDisplaysCorrectly(): void
    {
        $client = static::createClient();

        // Log in met bestaande gebruiker
        $user = $this->getUser();
        $client->loginUser($user);

        // Ga naar de kalenderpagina
        $crawler = $client->request('GET', '/training/calendar');

        // Controleer of de pagina goed laadt
        $this->assertResponseIsSuccessful();

        // Controleer of de kalender-HTML aanwezig is
        $this->assertSelectorExists('#calendar');

        // Controleer of de titel zichtbaar is
        $this->assertSelectorTextContains('h1', 'Trainingskalender');
    }

    private function getUser(): User
    {
        $container = static::getContainer();
        $user = $container->get('doctrine')->getRepository(User::class)
            ->findOneBy(['email' => 'robertdevilee@outlook.com']);

        $this->assertNotNull($user, 'Gebruiker robertdevilee@outlook.com moet bestaan voor deze test.');
        return $user;
    }
}
