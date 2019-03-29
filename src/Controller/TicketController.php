<?php

/* le namespace est le chemin où se trouve quoi ? */
namespace App\Controller;

/* 
le use est une route qui nous permet d'accéder au contenu d'une class dont on a besoin dans cette class 
y a t il besoin de tous les use listé ci dessous ????
*/
use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class TicketController extends AbstractController
{
    /**
     * @Route("/", name="ticket_index", methods={"GET"})
     */
    /*
    fonction déclarée comme publique ( accès de partout ), dont le nom est index,
    qui ne recoit aucun parametre mais qui renvoi une reponse
    */
    public function index(SessionInterface $session): Response
    {
        /* on attribut une valeur vide à la session d'attribut basket */
        $session->set('basket',array());
        /* une fois les actions réalisées et validées, on retourne sur la page de nom index 
        syntaxe correcte /ticket/index.html.twig ou ticket/index.html.twig */
        return $this->render('ticket/index.html.twig');
    }

}
