<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserType1;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;

class RegistrationController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    #[Route('/registration', name: 'app_registration')]
    public function index(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password

            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_ADMIN']);

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/profile', name: 'profile')]
    public function adminProfile(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        return $this->render('profile.html.twig', [
            'user' => $user,

        ]);
    }
    #[Route('/profile/{id}', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function editAdminProfile(Request $request, user $user, UserRepository $UserRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UserType1::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $UserRepository->save($user, true);

            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edit_profile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
