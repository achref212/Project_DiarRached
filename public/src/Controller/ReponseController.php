<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/', name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(MailerInterface $mailer, Request $request, $id, ReservationRepository $reservationRepository, ReponseRepository $ReponseRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        $reservation = $reservationRepository->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setReservation($reservation);
            $ReponseRepository->save($reponse, true);
            $reservation->setEtat("Traité");
            $reservationRepository->save($reservation, true);
            $this->sendEmail($mailer, $reservation->getEmail(), $reponse);
            return $this->redirectToRoute('app_reponse_success', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
            'reservation' => $reservation,

        ]);
    }
    public function sendEmail(MailerInterface $mailer, string $emailto, Reponse $reponse)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('immob.diyar.rached@gmail.com', 'Diar Rached'))
            ->to($emailto)
            ->subject('Message traité')
            ->htmlTemplate('reponse/email.html.twig')
            ->context([
                'reponse' => $reponse,
            ]);
        $mailer->send($email);

        return new Response("Success");
    }
    #[Route('/success', name: 'app_reponse_success')]
    public function success(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('reponse/success.html.twig');
    }

    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reponse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
}
