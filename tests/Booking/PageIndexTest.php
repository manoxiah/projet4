<?php
namespace App\Tests\Booking;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageIndexTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        /*Test de validation du bon affichage de la page index*/
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        /*Test de présence du texte Accueil*/
        $this->assertGreaterThan(0,$crawler->filter('html:contains("Accueil")')->count());
        /*Test de présence du texte Je réserve*/
        $this->assertGreaterThan(0,$crawler->filter('html:contains("Je réserve")')->count());
        /*Test de présence du lien contenant Accueil*/
        $this->assertGreaterThan(0,$crawler->filter('a:contains("Accueil")')->count());
        /*Test de présence du lien contenant Je réserve*/
        $this->assertGreaterThan(0,$crawler->filter('a:contains("Je réserve")')->count());
        /*Test du nombre de balise h4 contenant la phrase "Le musée en quelques mots"*/
        $this->assertGreaterThan(0,$crawler->filter('h4:contains("Le musée en quelques mots")')->count());
        /*Test du nombre de balise h4 contenant le mot "Bibliographie"*/
        $this->assertGreaterThan(0,$crawler->filter('h4:contains("Bibliographie")')->count());
        /*Test du nombre de balise p*/
        $this->assertGreaterThan(1,$crawler->filter('p:contains("")')->count());
        /*Test du nombre de balise strong*/
        $this->assertGreaterThan(3,$crawler->filter('strong:contains("")')->count());
        /*Test du nombre de lien de balise a*/
        $this->assertGreaterThan(7,$crawler->filter('a:contains("")')->count());
    }
}