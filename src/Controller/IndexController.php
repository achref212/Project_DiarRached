<?php
// src/Controller/IndexController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AppartementRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraints\NotNull;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/Admin', name: 'app_homeAdmin', methods: ['GET'])]
    public function indexAdmin(AppartementRepository $appartementRepository, ReservationRepository $reservationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $AppartementCount = [];
        $IRIS2 = $appartementRepository->findBy(['residence' => 2]);
        $IRIS1 = $appartementRepository->findBy(['residence' => 1]);
        $AppartementCount = [count($IRIS1), count($IRIS2)];
        $EtatCount = [];
        // Recherche des réservations avec l'état "Traité"
        $reservationsTraites = $reservationRepository->findBy(['etat' => 'Traité']);
        // Recherche des réservations avec l'état "Non Traité"
        $reservationsNonTraites = $reservationRepository->findBy(['etat' => 'Non Traité']);
        $EtatCount = [count($reservationsTraites), count($reservationsNonTraites)];

        $MesgOrReservCount = [];
        // Recherche des réservations avec résidence NULL
        $reservationsWithoutResidence = $reservationRepository->findBy(['residence' => [1, 2]]);

        // Recherche des réservations avec résidence non NULL
        $reservationsWithResidence = $reservationRepository->findBy(['residence' => null]);
        $MesgOrReservCount = [count($reservationsWithoutResidence), count($reservationsWithResidence)];

        $TypeCount = [];
        $S1 = $appartementRepository->findBy(['type' => 'S+1']);
        $S2 = $appartementRepository->findBy(['type' => 'S+2']);
        $S3 = $appartementRepository->findBy(['type' => 'S+3']);
        $Commercial = $appartementRepository->findBy(['type' => 'Commercial']);
        $Bureau = $appartementRepository->findBy(['type' => 'Bureau']);
        $JardinEnfant = $appartementRepository->findBy(['type' => 'Jardin d\'enfant']);
        $TypeCount = [count($S1), count($S2), count($S3), count($Commercial), count($Bureau), count($JardinEnfant)];
        //S+1
        $EtatS1Count = [];
        $disponibles1 = $appartementRepository->findBy(['etat' => 'Disponible', 'type' => 'S+1']);
        $vendus1 = $appartementRepository->findBy(['etat' => 'Vendu', 'type' => 'S+1']);
        $EtatS1Count = [count($disponibles1), count($vendus1)];
        //S+2
        $EtatS2Count = [];
        $disponibles2 = $appartementRepository->findBy(['etat' => 'Disponible', 'type' => 'S+2']);
        $vendus2 = $appartementRepository->findBy(['etat' => 'Vendu', 'type' => 'S+2']);
        $EtatS2Count = [count($disponibles2), count($vendus2)];
        //S+3
        $EtatS3Count = [];
        $disponibles3 = $appartementRepository->findBy(['etat' => 'Disponible', 'type' => 'S+3']);
        $vendus3 = $appartementRepository->findBy(['etat' => 'Vendu', 'type' => 'S+3']);
        $EtatS3Count = [count($disponibles3), count($vendus3)];
        //Jardin d\'enfant
        $EtatJdCount = [];
        $disponiblej = $appartementRepository->findBy(['etat' => 'Disponible', 'type' => 'Jardin d\'enfant']);
        $venduj = $appartementRepository->findBy(['etat' => 'Vendu', 'type' => 'Jardin d\'enfant']);
        $EtatJdCount = [count($disponiblej), count($venduj)];
        //Commercial
        $EtatComCount = [];
        $disponiblecom = $appartementRepository->findBy(['etat' => 'Disponible', 'type' => 'Commercial']);
        $venducom = $appartementRepository->findBy(['etat' => 'Vendu', 'type' => 'Commercial']);
        $EtatComCount = [count($disponiblecom), count($venducom)];
        //Bureau
        $EtatBurCount = [];
        $disponiblesbur = $appartementRepository->findBy(['etat' => 'Disponible', 'type' => 'Bureau']);
        $vendusbur = $appartementRepository->findBy(['etat' => 'Vendu', 'type' => 'Bureau']);
        $EtatBurCount = [count($disponiblesbur), count($vendusbur)];


        return $this->render(
            'Admin.html.twig',
            [
                'AppartementCount' => json_encode($AppartementCount),
                'EtatCount' => json_encode($EtatCount),
                'MesgOrReservCount' => json_encode($MesgOrReservCount),
                'TypeCount' => json_encode($TypeCount),
                'EtatS1Count' => json_encode($EtatS1Count),
                'EtatS2Count' => json_encode($EtatS2Count),
                'EtatS3Count' => json_encode($EtatS3Count),
                'EtatComCount' => json_encode($EtatComCount),
                'EtatJdCount' => json_encode($EtatJdCount),
                'EtatBurCount' => json_encode($EtatBurCount)
            ]
        );
    }
    #[Route('/A_propos', name: 'A_propos', methods: ['GET'])]
    public function a_propos(): Response
    {
        return $this->render('A-Propo.html.twig', []);
    }

    #[Route('/Diar_Rached', name: 'Diar_Rached', methods: ['GET'])]
    public function Diar_rached(): Response
    {
        return $this->render('Nos Références/Résidence-diar-rached.html.twig', []);
    }

    #[Route('/Kacem_City', name: 'Kacem_City', methods: ['GET'])]
    public function Kacem_City(): Response
    {
        return $this->render('Nos Références/Résidence-Kacem-city.html.twig', []);
    }

    #[Route('/Halima', name: 'Halima', methods: ['GET'])]
    public function Halima(): Response
    {
        return $this->render('Nos Références/Résidence-Halima.html.twig', []);
    }

    #[Route('/Kacem_Palace', name: 'Kacem_Palace', methods: ['GET'])]
    public function Kacem_Palace(): Response
    {
        return $this->render('Nos Références/Résidence-Kacem-Palace.html.twig', []);
    }
}
