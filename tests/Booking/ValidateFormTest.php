<?php
namespace App\Tests\Booking;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ValidateFormTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/booking/form');

        $form = $crawler->selectButton('Ajouter au panier')->form();
            $form['ticket[date_booking]']  = '23/03/2019';
            $form['ticket[email][first]'] = 'max@hot.fr';
            $form['ticket[email][second]'] = 'max@hot.fr';
            $form['ticket[name]'] = 'Lucas';
            $form['ticket[first_name]'] = 'marty';
            $form['ticket[country]'] = 'FR';
            $form['ticket[birth_date]'] = '20/03/2000';
            $form['ticket[type]'] = '0';

        $crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    
    }
}