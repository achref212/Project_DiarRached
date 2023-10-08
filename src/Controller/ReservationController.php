<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Appartement;
use App\Entity\Residence;
use App\Form\ContactType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


#[Route('/reservation')]
class ReservationController extends AbstractController
{

    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(Request $request, ReservationRepository $reservationRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $reservation = $reservationRepository->findAll();
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservation,
        ]);
    }
    #[Route('/Message', name: 'app_message', methods: ['GET'])]
    public function index2(ReservationRepository $reservationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('reservation/Message.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setEtat('Non Traité');
            $reservationRepository->save($reservation, true);
            return $this->redirectToRoute('app_reservation_success', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ContactType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setEtat('Non Traité');
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_Contact_success', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/Contact.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }


    #[Route('/success', name: 'app_reservation_success')]
    public function success(): Response
    {
        return $this->render('reservation/success.html.twig');
    }

    #[Route('/success_contact', name: 'app_Contact_success')]
    public function success1(): Response
    {
        return $this->render('reservation/success1.html.twig');
    }
    #[Route('/new1/{appartement}/{residence}', name: 'app_reservation_new1', methods: ['GET', 'POST'])]
    public function new1(Appartement $appartement, Residence $residence, Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $reservation->setAppartement($appartement);
        $reservation->setResidence($residence);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setEtat('Non Traité');
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_success', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'residenceId' => $residence->getId(),
            'appartementId' => $appartement->getId(),
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_reservation_delete1', methods: ['POST'])]
    public function delete1(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_message', [], Response::HTTP_SEE_OTHER);
    }
}
