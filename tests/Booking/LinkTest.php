<?php
namespace App\Tests\Booking;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LinkTest extends WebTestCase
{
    public function testLinkAccueil()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Accueil')->link();
        $crawler = $client->click($link);

        $this->assertSame("Le musée en quelques mots", $crawler->filter('h4')->text());
    }
    
    public function testLinkJeReserve()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        
        $link = $crawler->selectLink('Je réserve')->link();
        $crawler = $client->click($link);

        $this->assertSame("Formulaire de réservation", $crawler->filter('h4')->text());
    }
}