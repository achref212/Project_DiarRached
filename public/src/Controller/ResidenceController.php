<?php

namespace App\Controller;

use App\Entity\Residence;
use App\Form\ResidenceType;
use App\Repository\ResidenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AppartementRepository;

#[Route('/residence')]
class ResidenceController extends AbstractController
{
    #[Route('/', name: 'app_residence_index', methods: ['GET'])]
    public function index(ResidenceRepository $residenceRepository): Response
    {
        return $this->render('residence/index.html.twig', [
            'residences' => $residenceRepository->findAll(),
        ]);
    }
    #[Route('/all', name: 'app_residence_index1', methods: ['GET'])]
    public function index1(ResidenceRepository $residenceRepository): Response
    {
        return $this->render('residence/index1.html.twig', []);
    }
    #[Route('/new', name: 'app_residence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ResidenceRepository $residenceRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $residence = new Residence();
        $form = $this->createForm(ResidenceType::class, $residence);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $residenceRepository->save($residence, true);

            return $this->redirectToRoute('app_residence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('residence/new.html.twig', [
            'residences' => $residenceRepository->findAll(),
            'residence' => $residence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_residence_show', methods: ['GET'])]
    public function show(Residence $residence): Response
    {
        return $this->render('residence/show.html.twig', [
            'residence' => $residence,
        ]);
    }

    #[Route('/iris1', name: 'app_residence_iris1', methods: ['GET'])]
    public function iris1(ResidenceRepository $residenceRepository): Response
    {
        $residence = $residenceRepository->findOneBy(['nom' => 'IRIS1']);

        return $this->render('residence/show1.html.twig', [
            'residence' => $residence,
        ]);
    }

    #[Route('/iris2', name: 'app_residence_iris2', methods: ['GET'])]
    public function iris2(ResidenceRepository $residenceRepository): Response
    {
        $residence = $residenceRepository->findOneBy(['nom' => 'IRIS2']);

        return $this->render('residence/show1.html.twig', [
            'residence' => $residence,
        ]);
    }



    #[Route('/{id}', name: 'app_residence_show1', methods: ['GET'])]
    public function show1(string $id, ResidenceRepository $residenceRepository, AppartementRepository $appartementRepository): Response
    {
        $residence = $residenceRepository->findOneBy(['id' => '1']);

        if (!$residence) {
            throw $this->createNotFoundException('Residence not found');
        }

        // Récupérer les appartements associés à la résidence
        $appartements = $appartementRepository->findBy(['residence' => $residence]);

        return $this->render('residence/show.html.twig', [
            'residence' => $residence,
            'Appartements' => $appartements,
        ]);
    }




    #[Route('/{id}/edit', name: 'app_residence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Residence $residence, ResidenceRepository $residenceRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(ResidenceType::class, $residence);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $residenceRepository->save($residence, true);

            return $this->redirectToRoute('app_residence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('residence/edit.html.twig', [
            'residences' => $residenceRepository->findAll(),
            'residence' => $residence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_residence_delete', methods: ['POST'])]
    public function delete(Request $request, Residence $residence, ResidenceRepository $residenceRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $residence->getId(), $request->request->get('_token'))) {
            $apartments = $residence->getAppartements();

            foreach ($apartments as $apartment) {
                $entityManager->remove($apartment);
            }

            $entityManager->remove($residence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_residence_index');
    }
}
