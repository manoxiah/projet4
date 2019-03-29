<?php
namespace App\Tests\Booking;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/booking/form');
        /*Test de validation du bon affichage de la page index*/
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        /*Test de présence du nombre de balise input dans la page*/
        $this->assertGreaterThan(6,$crawler->filter('input:contains("")')->count());
        /*Test de présence du nombre de balise select dans la page*/
        $this->assertGreaterThan(1,$crawler->filter('select:contains("")')->count());
        /*Test de présence du nombre de balise select dans la page*/
        $this->assertGreaterThan(1,$crawler->filter('button:contains("")')->count());
    }
}