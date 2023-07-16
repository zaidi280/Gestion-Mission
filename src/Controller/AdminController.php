<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Employe;
use App\Form\AdminType;
use App\Form\EmployeType;
use App\Form\EmployeAffType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(EmployeRepository $doctrine, Security $security): Response
    {

        $Mission = $doctrine->findBy(['supprimer' => false]);
        return $this->render('admin/index.html.twig', [
            'Mission' => $Mission,

        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/supp', name: 'app_supp')]
    public function indexsupp(EmployeRepository $doctrine): Response
    {


        $Mission = $doctrine->findBy(['supprimer' => false]);
        return $this->render('admin/supprimeremp.html.twig', [
            'Mission' => $Mission,

        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/aff', name: 'app_AffEmp')]
    public function indexaff(EmployeRepository $doctrine): Response
    {


        $Mission = $doctrine->findBy(['supprimer' => false, 'user' => null]);
        return $this->render('admin/AffecterEmp.html.twig', [
            'Mission' => $Mission,

        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/{id<\d+>}/edit', name: 'emp_modif')]
    public function edit(Employe $Mission, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(EmployeAffType::class, $Mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$em->persist($Mission);
            $em->flush();
            return $this->redirectToRoute('app_AffEmp');
        }
        return $this->renderForm('admin/editAff.html.twig', ['formMission' => $form]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/{id<\d+>}/deleteComp', name: 'comp_supp')]
    public function deletecomp(User $Mission, EntityManagerInterface $entityManager): Response
    {

        $Mission->setSupprimer(true);

        $entityManager->persist($Mission);
        $entityManager->flush();
        return $this->redirectToRoute('app_suppcomp');
    }
    #[Route('/admin/suppcomp', name: 'app_suppcomp')]
    public function suppcomp(UserRepository $doctrine): Response
    {
        $user = $doctrine->findBy(['supprimer' => false]);
        return $this->render('admin/suppcompte.html.twig', [
            'user' => $user,

        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/{id<\d+>}/deleteEmp', name: 'emp_supp')]
    public function deleteEmp(Employe $Mission, EntityManagerInterface $entityManager): Response
    {

        $Mission->setSupprimer(true);

        $entityManager->persist($Mission);
        $entityManager->flush();
        return $this->redirectToRoute('app_supp');
    }
    #[Route('/admin/modifiermdp', name: 'app_modif')]
    public function modifiermdp(UserRepository $doctrine): Response
    {
        $user = $doctrine->findBy(['supprimer' => false]);
        return $this->render('admin/modifiermdp.html.twig', [
            'user' => $user,

        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/create', name: 'Ajout_emp')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $mission = new Employe;


        $form = $this->createForm(EmployeType::class, $mission);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($mission);
            $em->flush();

            return $this->redirectToRoute('app_AffEmp');
        }

        return $this->renderForm('admin/create.html.twig', ['formMission' => $form]);
    }
    //form edit
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/emp/{id<\d+>}/edit', name: 'emp_edit')]
    public function EditEmp(Employe $Mission, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(EmployeType::class, $Mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$em->persist($Mission);
            $em->flush();
            return $this->redirectToRoute('modif_emp');
        }
        return $this->renderForm('admin/modemp.html.twig', ['formEmploye' => $form]);
    }
    //index modifier
    #[Route('/admin/modifier/emp', name: 'modif_emp')]
    public function ModifEmp(EmployeRepository $doctrine): Response
    {
        $empl = $doctrine->findBy(['supprimer' => false]);
        return $this->render('admin/modifieremp.html.twig', [
            'empl' => $empl,

        ]);
    }
    #[Route('/admin/{id<\d+>}/modifier', name: 'user_edit')]
    public function modifier(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em)
    {
        $form = $this->createForm(RegistrationFormType::class, $user);



        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $em->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/editComp.html.twig', ['registrationForm' => $form]);
    }
}
