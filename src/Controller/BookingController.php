<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Charge;
use Stripe\Error\Base;
use Stripe\Stripe;


/* annotation permettent l'appel de la class*/
/**
 * @Route("/booking")
 */
/* declaration de la class*/
class BookingController extends AbstractController
{


    /**
     * @Route("/form", name="booking_form", methods={"GET","POST"})
     * 
     */
    public function form(SessionInterface $session, Request $request): Response
    {
        /* création de la variable $basket, contenant le contenu de  $_SESSION['basket']*/
        $basket = $session->get('basket');
        /* test du contenu de la variable $basket avec un if */
        if($basket === NULL)
        {
            /* declaration de la variable $basket contenant un tableau , array */
            $basket = array();
            /* attribution a $_SESSION['basket'] le contenu de $basket, vide ici */
            $session->set("basket",$basket);
        }
        /* class Ticket instaciée et mise de l'objet dans la variable $ticket */
        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class, $ticket);
        /* chargement des donnees utilisateur " ticket " dans objet form ( verification ) */
        $form->handleRequest($request);

        /* verifiaction des données du formulaire et son envoi */
        if ($form->isSubmitted() && $form->isValid())
        {
            /* creation de la variable $dateToDay, attribution de la date du jour */
            $dateToDay = new \DateTime();
            /* creation de a variable $dateBirhtDay en lui attribuent la valeur que contient $ticket->getBirthDate() 
            avec le format renseigné */
            $dateBirhtDay = \DateTime::createFromFormat('d/m/Y',$ticket->getBirthDate());
            /* creation de a variable $dateBooking en lui attribuent la valeur que contient $ticket->getDateBooking() 
            avec le format renseigné */
            $dateBooking = \DateTime::createFromFormat('d/m/Y',$ticket->getDateBooking());
            /* creation de la variable $interval qui nous donnera l age de la personne,
            difference entre date du jour et la date de naissance */
            $interval = $dateToDay->diff($dateBirhtDay);
            /* creation de la variable $diffdate avec l attribution de la valeur années de $interval */
            $diffdate = $interval->y;

            /* verification de la valeur de $diffdate pour donner un prix au ticket en fonction de l age de la personne 
            inférieur à 4 ans */
            if($diffdate < 4 )
            {
                /* attribution du prix au ticket, ici 0*/ 
                $ticket->setPrice(0);
            }
            elseif($diffdate >= 4 && $diffdate < 12)
            {
                $ticket->setPrice(8);
            }
            elseif($diffdate >= 12 && $diffdate < 60)
            {
                $ticket->setPrice(16);
            }
            else
            {
                $ticket->setPrice(12);
            }
            /* verification de la valeur de getType() dans l objet $ticket, ici 0, 
            en fonction de la valeur, on attribut un nouveau prix au ticket */
            if($ticket->getType() == 0)
            {
                $ticket->setPrice($ticket->getPrice());
            }
            elseif($ticket->getType() == 1)
            {
                $ticket->setPrice($ticket->getPrice()/2);
            }
            elseif ($ticket->getType() == 2)
            {
                $ticket->setPrice(10);
            }
            else
            {
                /* si la valeur n est pas dans la liste de tests, on affiche un message d erreur addFlash(),
                et on retourne sur la page du formulaire tout en conservent les donnees entrées par l utilisateur */
                $this->addFlash("error", "Le type de billet n'est pas valide, merci de choisir un type de billet existant");
                return $this->render('booking/form.html.twig', ['form' => $form->createView(), 'basket' => $basket]);
            }
            
            /* cette fonction php array_push, nous permet d ajouter un attribut ( $ticket ) de plus au tableau ( $basket ) */
            array_push($basket,$ticket);
            /* mise à jour des données de $_SESSION['basket'] */
            $session->set("basket",$basket);
            /* message de validation */
            $this->addFlash("success", "Le billet a bien été ajouté au panier");
        }
        /* retour à la page formulaire, tout en gardent les données entrees par l utilisateur */
        return $this->render('booking/form.html.twig', ['form' => $form->createView(), 'basket' => $basket]);
    }

    /**
     * @Route("/payment", name="booking_payment", methods={"POST"})
     */
    public function payment(SessionInterface $session, Request $request, \Swift_Mailer $mailer): Response
    {
        $basket = $session->get('basket');
        /* création de la variable $entityManager contenant la connexion a doctrine */
        $entityManager = $this->getDoctrine()->getManager();

        if($basket != null)
        {
            /* partie de paiement avec strype */
            \Stripe\Stripe::setApiKey("sk_test_VNuhs4dt8QoVjkkluLyaVBFP");

            $token = $request->request->get('stripeToken');
            $total = 0;

            foreach ($basket as $ticket)
            {
                $entityManager->persist($ticket);
                $total = $total+$ticket->getPrice();
            }
            $entityManager->flush();
            $charge = \Stripe\Charge::create([
                'amount' => $total*100,
                'currency' => 'eur',
                'source' => $token,
            ]);
            /* réinitialisation de $_SESSION['basket'], vide */
            $session->set('basket',array());
            $this->addFlash("success", "Votre achat a été effectué");
            
            /* preparation du billet de confirmation */
            $message = new \Swift_Message('Votre ticket');
            /* adresse de messagerie d envoi */
            $message->setFrom('maxbonbon2@hotmail.fr')
            /* adresse de messagerie de récuperation du billet */
            ->setTo($basket[0]->getEmail())

            /* contenu du billet */
            ->setBody(
                $this->renderView(
                    'booking/email_confirm.html.twig', array('basket' => $basket)
                ),
                'text/html'
            );
        /* fonction php permettant d envoyer le mail de confirmation contenent le ou les billets réservé (s) */
        $mailer->send($message);
            return $this->render('booking/resume.html.twig', ['basket' => $basket]);
        }
        else
        {
            $this->addFlash("error", "Il n'y a pas de ticket dans votre panier");
            return $this->redirectToRoute('booking_form');
        }

    }


    /**
     * @Route("/delete_to_basket/{id}", name="booking_delete_to_basket", methods={"GET"})
     */
    public function delete_to_basket(SessionInterface $session, $id): Response
    {
        $basket = $session->get('basket');
        /* suppression du ticket cliqué par l utilisateur dans le pannier, ici $basket(avec en parametre id du ticket) */
        unset($basket[$id]);
        /* maj de $basket */
        $session->set("basket",$basket);
        $this->addFlash("success", "Votre billet a été retiré de votre panier");
        return $this->redirectToRoute('booking_form');
    }

    /**
     * @Route("/verify_nb_ticket/{day}", name="booking_verify_nb_ticket", methods={"GET"})
     */
    public function verifyNbTicket($day)
    {
        $dateToDay = new \DateTime();
        $entityManager = $this->getDoctrine()->getManager();
        $dateBooking = \DateTime::createFromFormat('d-m-Y',$day);
        /* verification du nombre de tickets vendus le jour choisit par l utilisateur */
        $nbTicket = $entityManager->getRepository(Ticket::class)->countTicketByDay($dateBooking->format('d/m/Y'));
        /*$nbTicket = 1001;*/

        if ($nbTicket <= 1000)
        { 
            $diff = $dateToDay->diff( $dateBooking );
            $diffDays = (integer)$diff->format( "%R%a" );


            if($diffDays == 0)
            {
                $dateToDay14h = new \DateTime();
                $dateToDay14h->setTime(14, 0, 0);

                if($dateToDay >= $dateToDay14h)
                { 
                    return new Response("demi journee");
                }
            }
        }
        else
        {
            return new Response("il y a plus de 1000 tickets.");
        }
        return new Response("ok");
    }

}