<?php
namespace App\Tests\Booking;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageFormTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/booking/form');
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
        /*Test du nombre de balise img*/
        $this->assertGreaterThan(6,$crawler->filter('img:contains("")')->count());
        /*Test du nombre de balise img*/
        $this->assertGreaterThan(0,$crawler->filter('head:contains("")')->count());
        /*Test du nombre de balise img*/
        $this->assertGreaterThan(0,$crawler->filter('body:contains("")')->count());
        /*Test du nombre de balise img*/
        $this->assertGreaterThan(0,$crawler->filter('header:contains("")')->count());
        /*Test du nombre de balise img*/
        $this->assertGreaterThan(0,$crawler->filter('nav:contains("")')->count());
        /*Test du nombre de balise img*/
        $this->assertGreaterThan(0,$crawler->filter('footer:contains("")')->count());

    }
}