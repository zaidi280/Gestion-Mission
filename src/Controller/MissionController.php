<?php

namespace App\Controller;

use DateTime;
use App\Entity\Employe;
use App\Entity\Rapport;
use App\Entity\Voiture;
use App\Form\MissionType;
use App\Form\RapportType;
use App\Form\ModifMissType;
use App\Entity\DemandeMission;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\EmployeRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\DemandeMissionRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MissionController extends AbstractController
{
    #[Route('/mission/sg', name: 'app_mission')]
    public function index(DemandeMissionRepository $doctrine, Request $request, EmployeRepository $emp): Response
    {

        $Mission = $doctrine->findBy(['supprime' => false]);
        $employe = $emp->findAll();
        // $pagination = $paginator->paginate(
        //     $doctrine->PaginationQuery(),
        //     $request->query->get('page', 1),
        //     5

        // );
        return $this->render('mission/indexglobal.html.twig', [

            'Mission' => $Mission,
            'employe' => $employe
        ]);
    }
    #[Route('/mission/user', name: 'app_userindex')]
    public function indexutilisateur(DemandeMissionRepository $em, Security $security): Response
    {
        // récupérer l'utilisateur actuel
        $user = $security->getUser();
        $Mission = $em->findBy(['supprime' => false, 'user' => $user]);
        return $this->render('mission/index.html.twig', [
            'Mission' => $Mission,

        ]);
    }
    #[Route('/mission/{id<[0-9]+>}', name: 'show_Mission')]
    public function show(ManagerRegistry $doctrine, $id, EmployeRepository $emp, EntityManagerInterface $entityManager, Request $request)
    {
        $request->setLocale('ar');
        $repository = $doctrine->getRepository(DemandeMission::class);
        $Mission = $repository->find($id);
        $qb = $entityManager->createQueryBuilder();
        $qb->select('dm', 'e.NumTell', 'e.NumFax', 'e.Nom_Prenom', 'p.grade', "a.dep")
            ->from(DemandeMission::class, 'dm')
            ->leftJoin('dm.user', 'u')
            ->leftJoin('App\Entity\Employe', 'e', 'WITH', 'e.user = u.id')
            ->leftJoin('e.profession', 'p')
            ->leftJoin('e.departement', 'a')
            ->where('dm.id = :id')
            ->setParameter('id', $id);
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        return $this->render('mission/show.html.twig', [
            'Mission' => $Mission,
            'NumTell' => $result['NumTell'],
            'NumFax' => $result['NumFax'],
            'Nom_Prenom' => $result['Nom_Prenom'],
            'grade' => $result['grade'],
            'dep' => $result['dep'],


        ]);
    }
    #[IsGranted('ROLE_EMPLOYE')]
    #[Route('/mission/create', name: 'Mission_create')]
    public function create(Request $request, EntityManagerInterface $em, Security $security): Response
    {

        $mission = new DemandeMission;


        $form = $this->createForm(MissionType::class, $mission);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $request->request->all();
            $s = isset($formData['champ']) ? implode(', ', $formData['champ']) : null;

            if ($s != null) {

                $mission->setAccompext($s);
            }


            $mission->setNumMiss(str_split(uniqid(), 6)[0]);
            $mission->setDateCreation(new DateTime());
            $user = $security->getUser();
            $mission->setUser($user);

            $em->persist($mission);
            $em->flush();




            return $this->redirectToRoute('app_userindex');
        }

        return $this->renderForm('mission/create.html.twig', ['formMission' => $form]);
    }
    #[IsGranted('ROLE_EMPLOYE')]
    #[Route('/mission/{id<\d+>}/edit', name: 'Mission_edit')]
    public function edit(DemandeMission $Mission, Request $request, EntityManagerInterface $em)
    {
        $existingAccompext = explode(', ', $Mission->getAccompext());
        $form = $this->createForm(ModifMissType::class, $Mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$em->persist($Mission);



            $formData = $request->request->all();
            $s = isset($formData['champ']) ? implode(', ', $formData['champ']) : null;

            if ($s != null) {

                $Mission->setAccompext($s);
            }



            $em->flush();
            return $this->redirectToRoute('app_userindex');
        }
        return $this->renderForm('mission/edit.html.twig', ['formMission' => $form, 'Mission' => $Mission, 'existingAccompext' => $existingAccompext,]);
    }
    #[IsGranted('ROLE_EMPLOYE')]
    #[Route('/mission/{id}/delete', name: 'Mission_delete')]
    public function delete(DemandeMission $Mission, EntityManagerInterface $entityManager): Response
    {

        $Mission->setSuprrimer(true);

        $entityManager->persist($Mission);
        $entityManager->flush();
        return $this->redirectToRoute('app_userindex');
    }
    #[IsGranted('ROLE_CHEF_DE_PARC')]
    #[Route('/affecter/{id}', name: 'Aff_Voiture')]
    public function Affecte(ManagerRegistry $doctrine, Request $request, VoitureRepository $voitureRepository, EmployeRepository $employeRepository, DemandeMission $demandeMission): Response
    {
        $repository = $doctrine->getRepository(DemandeMission::class);
        $aff = $repository->findBy(['voiture' => null]);
        $voitures = $voitureRepository->findAll();
        $employes = $employeRepository->findAll();

        //dd($aff);
        //$aff = $repository->find($id);

        $m = $request->query->get('input');

        return $this->render('voiture/AffMiss.html.twig', [
            'aff' => $aff, 'm' => $m, 'voitures' => $voitures, 'employes' => $employes, 'Miss' => $demandeMission
        ]);
    }
    #[IsGranted('ROLE_CHEF_DE_PARC')]
    #[Route('/aff/{id}', name: 'Affecter_Voiture')]
    public function Aff(VoitureRepository $voitureRepository, EmployeRepository $employeRepository, DemandeMission $demandeMission, DemandeMissionRepository $demRepository)
    {
        $voiture = $voitureRepository->find($_POST['voiture']);
        $employe = $employeRepository->find($_POST['employe']);

        $missions =  $demRepository->findBy(["voiture" => $voiture, "employe" => $employe]);



        foreach ($missions as $mission) {

            if ($mission->getDateDebut() == $demandeMission->getDateDebut()) {
                if ($mission->getDestination() == $demandeMission->getDestination() && $mission->getEmploye()  && $mission->getVoiture() && count($missions) <= 4) {

                    $demandeMission->setEmploye($employe);
                    $demandeMission->setVoiture($voiture);
                } else {

                    $this->addFlash('error', 'chauffeur et voiture deja reserver');
                    return $this->redirectToRoute('Aff_Voiture', ['id' => $demandeMission->getId()]);
                }
            } else if ($demandeMission->getDateDebut() <= $mission->getDateDebut() && $demandeMission->getDateFin() <= $mission->getDateFin() || $demandeMission->getDateDebut() >= $mission->getDateDebut() && $demandeMission->getDateFin() <= $mission->getDateFin() || $demandeMission->getDateDebut() <= $mission->getDateFin() && $demandeMission->getDateFin() > $mission->getDateFin()) {
                $this->addFlash('error', 'chauffeur et voiture deja reserver');
                return $this->redirectToRoute('Aff_Voiture', ['id' => $demandeMission->getId()]);
            }
        }
        $demandeMission->setEmploye($employe);
        $demandeMission->setVoiture($voiture);





        $demRepository->save($demandeMission, true);
        return $this->redirectToRoute('app_voit_miss');
    }
    #[IsGranted('ROLE_EMPLOYE')]
    #[Route('/mission/create/{id}/rapp', name: 'rapp_create')]
    public function createRapp(int $id, Request $request, EntityManagerInterface $em, DemandeMission $rapp, Security $security): Response
    {


        $mission = new Rapport;
        $mission->getRapMiss($rapp);


        $form = $this->createForm(RapportType::class, $mission);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            $mission->setUser($user);
            $mission->addDemandeMission($rapp);
            $em->persist($mission);
            $em->flush();

            return $this->redirectToRoute('app_userindex');
        }

        return $this->renderForm('mission/createrapp.html.twig', ['formMission' => $form]);
    }
    #[IsGranted('ROLE_SCGEN')]
    #[Route('/sec/valid', name: 'valid_miss')]
    public function valide(DemandeMissionRepository $doctrine, EmployeRepository $emp): Response
    {

        $Mission = $doctrine->findBy(['valider' => false]);
        $employe = $emp->findAll();

        return $this->render('mission/validMiss.html.twig', [
            'Mission' => $Mission,
            'employe' => $employe



        ]);
    }
    #[IsGranted('ROLE_SCGEN')]
    #[Route('/mission/{id}/valid', name: 'mission_valid')]
    public function Missvalid(DemandeMission $Mission, EntityManagerInterface $entityManager, MailerInterface $mailer, EmployeRepository $emp): Response
    {
        $employe = $emp->findAll();

        $Mission->setValider(true);

        $entityManager->persist($Mission);
        $entityManager->flush();

        $email = (new Email())
            ->from('zaidisaif95@gmail.com')
            ->to($Mission->getUser()->getEmail())
            ->subject('Confirmation de mission')
            ->html(
                $this->renderView(
                    'mission/confirmation_mission.html.twig',
                    [
                        'Mission' => $Mission,
                        'employe' => $employe
                    ]
                )
            );

        $mailer->send($email);

        $this->addFlash('success', 'Demande ' . $Mission->getNumMiss() . ' validée.');
        return $this->redirectToRoute('valid_miss');
    }
    #[IsGranted('ROLE_EMPLOYE')]
    #[Route('/mission/stats', name: 'miss_stats')]
    public function MissStats(DemandeMissionRepository $missrepo,  Security $security)
    {
        $user = $security->getUser();
        $mission = $missrepo->findByDate($user);
        $dates = [];
        $misscount = [];

        foreach ($mission as $miss) {

            $dates[] = $miss['Month'];
            $misscount[] = $miss['count'];
        }
        return $this->render('mission/stats.html.twig', [
            'dates' => json_encode($dates),
            'misscount' => json_encode($misscount)
        ]);
    }
    #[IsGranted('ROLE_SCGEN')]
    #[Route('/mission/stats/sg', name: 'sg_stats')]
    public function SgStats(DemandeMissionRepository $missrepo)
    {

        $mission = $missrepo->findByDateSg();
        $datessg = [];
        $misscountsg = [];

        foreach ($mission as $miss) {

            $datessg[] = $miss['Month'];
            $misscountsg[] = $miss['count'];
        }
        return $this->render('mission/sgstats.html.twig', [
            'datessg' => json_encode($datessg),
            'misscountsg' => json_encode($misscountsg)
        ]);
    }
}
