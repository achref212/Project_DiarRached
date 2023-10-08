<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Form\AppartementType;
use App\Repository\AppartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ResidenceRepository;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/appartement')]
class AppartementController extends AbstractController
{
    #[Route('/2', name: 'app_appartement_index', methods: ['GET'])]
    public function index1(AppartementRepository $appartementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $appartements = $appartementRepository->findBy(['residence' => 2]); // Remplacez 1 par l'ID de la résidence souhaitée
        $appartements1 = $appartementRepository->findBy(['type' => 'S+1', 'residence' => 2]);
        $appartements2 = $appartementRepository->findBy(['type' => 'S+2', 'residence' => 2]);
        $appartements3 = $appartementRepository->findBy(['type' => 'S+3', 'residence' => 2]);
        $appartementsc = $appartementRepository->findBy(['type' => 'Commercial', 'residence' => 2]);

        $appartements1 = $paginator->paginate($appartements1, $request->query->getInt('page', 1), 15);
        $appartements2 = $paginator->paginate($appartements2, $request->query->getInt('page', 1), 15);
        $appartements3 = $paginator->paginate($appartements3, $request->query->getInt('page', 1), 15);
        $appartementsc = $paginator->paginate($appartementsc, $request->query->getInt('page', 1), 15);
        return $this->render('appartement/index2.html.twig', [
            'appartements' => $appartements,
            'appartements1' => $appartements1,
            'appartements2' => $appartements2,
            'appartements3' => $appartements3,
            'appartementsc' => $appartementsc,
        ]);
    }
    #[Route('/1', name: 'app_appartement_index1', methods: ['GET'])]
    public function index2(AppartementRepository $appartementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $appartements = $appartementRepository->findBy(['residence' => 1]); // Remplacez 1 par l'ID de la résidence souhaitée
        $appartements1 = $appartementRepository->findBy(['type' => 'S+1', 'residence' => 1]);
        $appartements2 = $appartementRepository->findBy(['type' => 'S+2', 'residence' => 1]);
        $appartements3 = $appartementRepository->findBy(['type' => 'S+3', 'residence' => 1]);
        $appartementsc = $appartementRepository->findBy(['type' => 'Commercial', 'residence' => 1]);
        $appartementsb = $appartementRepository->findBy(['type' => 'Bureau', 'residence' => 1]);

        $appartementsj = $appartementRepository->findBy(['type' => 'Jardin d\'enfant']);

        $appartements1 = $paginator->paginate($appartements1, $request->query->getInt('page', 1), 15);
        $appartements2 = $paginator->paginate($appartements2, $request->query->getInt('page', 1), 15);
        $appartements3 = $paginator->paginate($appartements3, $request->query->getInt('page', 1), 15);
        $appartementsc = $paginator->paginate($appartementsc, $request->query->getInt('page', 1), 15);
        $appartementsb = $paginator->paginate($appartementsb, $request->query->getInt('page', 1), 15);
        $appartementsj = $paginator->paginate($appartementsj, $request->query->getInt('page', 1), 15);
        return $this->render('appartement/index1.html.twig', [
            'appartements' => $appartements,
            'appartements1' => $appartements1,
            'appartements2' => $appartements2,
            'appartements3' => $appartements3,
            'appartementsc' => $appartementsc,
            'appartementsb' => $appartementsb,
            'appartementsj' => $appartementsj,
        ]);
    }

    #[Route('/Residence/{id}', name: 'app_appartement_iris1', methods: ['GET'])]
    public function iris($id, AppartementRepository $appartementRepository, ResidenceRepository $residenceRepository): Response
    {
        $residence = $residenceRepository->find($id);
        $appartements = $appartementRepository->findBy(['residence' => $residence]);

        return $this->render('appartement/iris1.html.twig', [
            'appartements' => $appartements,
            'residence' => $residence,

        ]);
    }

    #[Route('/Residence/{id}/new', name: 'app_appartement_new1', methods: ['GET', 'POST'])]
    public function new(Request $request, AppartementRepository $appartementRepository, ResidenceRepository $residenceRepository, $id, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $residence = $residenceRepository->find($id);
        $appartements = $appartementRepository->findBy(['residence' => $residence]);
        $appartements = $paginator->paginate($appartements, $request->query->getInt('page', 1), 15);
        $appartement = new Appartement();

        $appartement->setResidence($residence);

        $form = $this->createForm(AppartementType::class, $appartement);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $appartementRepository->save($appartement, true);

            return $this->redirectToRoute('app_appartement_new1', ['id' => $appartement->getResidence()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appartement/new.html.twig', [
            'appartements' => $appartements,
            'residence' => $residence,
            'form' => $form,
        ]);
    }

    #[Route('/Residence/{residenceId}/appartement/{appartementId}/edit', name: 'app_appartement_edit1', methods: ['GET', 'POST'])]
    public function edit1(Request $request, int $residenceId, int $appartementId, AppartementRepository $appartementRepository, ResidenceRepository $residenceRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $residence = $residenceRepository->find($residenceId);
        $appartement = $appartementRepository->find($appartementId);
        $appartements = $appartementRepository->findBy(['residence' => $residence]);
        $appartements = $paginator->paginate($appartements, $request->query->getInt('page', 1), 15);
        // Check if the appartement exists and is associated with the residence


        $form = $this->createForm(AppartementType::class, $appartement);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $appartementRepository->save($appartement, true);

            return $this->redirectToRoute('app_appartement_new1', ['id' => $appartement->getResidence()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appartement/edit.html.twig', [
            'appartements' => $appartements,
            'residence' => $residence,
            'form' => $form,
        ]);
    }




    #[Route('/{id}', name: 'app_appartement_show', methods: ['GET'])]
    public function show(Appartement $appartement): Response
    {
        return $this->render('appartement/show.html.twig', [
            'appartement' => $appartement,
        ]);
    }


    #[Route('/{id}', name: 'app_appartement_delete', methods: ['POST'])]
    public function delete(Request $request, Appartement $appartement, AppartementRepository $appartementRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appartement->getId(), $request->request->get('_token'))) {
            // Get the image file name associated with the Appartement


            // Clear the image file name from the Appartement entity
            $appartement->setImage(null);

            // Remove the Appartement entity from the database
            $entityManager->remove($appartement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appartement_iris1', ['id' => $appartement->getResidence()->getId()], Response::HTTP_SEE_OTHER);
    }
}
