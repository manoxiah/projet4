<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Charge;
use Stripe\Error\Base;
use Stripe\Stripe;



/**
 * @Route("/booking")
 */

class BookingController extends AbstractController
{

    /**
     * @Route("/form", name="booking_form", methods={"GET","POST"})
     * doc date time: http://php.net/manual/fr/datetime.diff.php
     */
    public function form(Request $request, \Swift_Mailer $mailer): Response
    {
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('maxbonbon@hotmail.fr')
        ->setTo('maxbonbon@hotmail.fr')
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'ticket/index.html.twig'
            ),
            'text/html'
        );

    $mailer->send($message);

        $session = new Session();
        $basket = $session->get('basket');
        if($basket === NULL)
        {
            $basket = array();
            $session->set("basket",$basket);
        }

        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $dateToDay = new \DateTime();

            $dateBirhtDay = \DateTime::createFromFormat('d/m/Y',$ticket->getBirthDate());
            $dateBooking = \DateTime::createFromFormat('d/m/Y',$ticket->getDateBooking());

            $interval = $dateToDay->diff($dateBirhtDay);
            $diffdate = $interval->y;
            $entityManager = $this->getDoctrine()->getManager();


            if($diffdate < 4 )
            {
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
                $this->addFlash("error", "Le type de billet n'est pas valide, merci de choisir un type de billet existant");
                return $this->render('booking/form.html.twig', ['form' => $form->createView(), 'basket' => $basket]);
            }
            

            array_push($basket,$ticket);
            $session->set("basket",$basket);
            $this->addFlash("success", "Le billet a bien été ajouté au panier");
        }

        return $this->render('booking/form.html.twig', ['form' => $form->createView(), 'basket' => $basket]);
    }

    /**
     * @Route("/payment", name="booking_payment", methods={"POST"})
     */
    public function payment(Request $request): Response
    {
        $session = new Session();
        $basket = $session->get('basket');
        $entityManager = $this->getDoctrine()->getManager();

        if($basket != null)
        {
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
                'description' => 'Example charge',
                'source' => $token,
            ]);
            $session->set('basket',array());
            $this->addFlash("success", "Votre achat a été effectué");

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
    public function delete_to_basket($id): Response
    {
        $session = new Session();
        $basket = $session->get('basket');
        unset($basket[$id]);
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
        $nbTicket = $entityManager->getRepository(Ticket::class)->countTicketByDay($dateBooking->format('d/m/Y'));
        /*$nbTicket = 1001;*/

        if ($nbTicket <= 1000)
        { 
            $diff = $dateToDay->diff( $dateBooking );
            $diffDays = (integer)$diff->format( "%R%a" );


            if($diffDays == 0)
            {
                $dateToDay14h = new \DateTime();
                $dateToDay14h->setTime(10, 0, 0);

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