<?php

namespace App\Controller;

use App\Entity\DemandeMission;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\DemandeMissionRepository;
use App\Repository\EmployeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PersonnelController extends AbstractController
{
    #[IsGranted('ROLE_PERSONNEL')]
    #[Route('/ord/mission/index', name: 'ord-miss')]
    public function OrdMission(DemandeMissionRepository $doctrine, EmployeRepository $emp): Response
    {

        $Mission = $doctrine->findBy(['valider' => true, 'ordvalid' => false]);
        $employe = $emp->findAll();

        return $this->render('ordmission/ordmiss.html.twig', [
            'Mission' => $Mission,
            'employe' => $employe
        ]);
    }
    #[IsGranted('ROLE_PERSONNEL')]
    #[Route('/ordmiss/index', name: 'index_ord')]
    public function IndexOrdMission(DemandeMissionRepository $doctrine, EmployeRepository $emp): Response
    {

        $Mission = $doctrine->findBy(['valider' => true]);

        $employe = $emp->findAll();
        return $this->render('ordmission/indexordmiss.html.twig', [
            'Mission' => $Mission,
            'employe' => $employe

        ]);
    }
    #[IsGranted('ROLE_PERSONNEL')]
    #[Route('/mission/{id}/ord', name: 'ord_missfiche')]
    public function show(ManagerRegistry $doctrine, $id, EntityManagerInterface $entityManager)
    {
        $repository = $doctrine->getRepository(DemandeMission::class);
        $Mission = $repository->find($id);
        $qb = $entityManager->createQueryBuilder();
        $qb->select('dm', 'e.Nom_Prenom', 'e.numcarte', 'p.grade')
            ->from(DemandeMission::class, 'dm')
            ->leftJoin('dm.user', 'u')
            ->leftJoin('App\Entity\Employe', 'e', 'WITH', 'e.user = u.id')
            ->leftJoin('e.profession', 'p')
            ->where('dm.id = :id')
            ->setParameter('id', $id);
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        return $this->render('ordmission/ficheordmiss.html.twig', [
            'Mission' => $Mission,
            'numcarte' => $result['numcarte'],
            'grade' => $result['grade'],
            'Nom_Prenom' => $result['Nom_Prenom']

        ]);
    }
    #[IsGranted('ROLE_PERSONNEL')]
    #[Route('/mission/{id<[0-9]+>}/rapp', name: 'ordrapp_miss')]
    public function showrapp(ManagerRegistry $doctrine, $id)
    {
        $repository = $doctrine->getRepository(DemandeMission::class);
        $Mission = $repository->find($id);
        return $this->render('ordmission/ordrappmiss.html.twig', [
            'Mission' => $Mission,
        ]);
    }
    #[IsGranted('ROLE_PERSONNEL')]
    #[Route('/mission/{id}/ordvalid', name: 'ord_valid')]
    public function Missvalid(DemandeMission $Mission, EntityManagerInterface $entityManager): Response
    {

        $Mission->setOrdvalid(true);

        $entityManager->persist($Mission);
        $entityManager->flush();


        $this->addFlash('success', 'Demande ' . $Mission->getNumMiss() . ' validée.');
        return $this->redirectToRoute('ord-miss');
    }

    #[IsGranted('ROLE_PERSONNEL')]
    #[Route('/ord/mission/index/imprimer', name: 'indexord-miss')]
    public function IndexImpOrdMission(DemandeMissionRepository $doctrine, EmployeRepository $emp): Response
    {
        $Mission = $doctrine->findBy(['valider' => true, 'ordvalid' => true]);
        $employe = $emp->findAll();
        return $this->render('ordmission/imprindexord.html.twig', [
            'Mission' => $Mission,
            'employe' => $employe
        ]);
    }
    #[IsGranted('ROLE_PERSONNEL')]
    #[Route('/mission/{id<[0-9]+>}/impordmiss', name: 'imprord_miss')]
    public function showordmiss(ManagerRegistry $doctrine, $id, EntityManagerInterface $entityManager)
    {
        $repository = $doctrine->getRepository(DemandeMission::class);
        $Mission = $repository->find($id);
        $qb = $entityManager->createQueryBuilder();
        $qb->select('dm', 'e.Nom_Prenom', 'e.numcarte', 'p.grade')
            ->from(DemandeMission::class, 'dm')
            ->leftJoin('dm.user', 'u')
            ->leftJoin('App\Entity\Employe', 'e', 'WITH', 'e.user = u.id')
            ->leftJoin('e.profession', 'p')
            ->where('dm.id = :id')
            ->setParameter('id', $id);
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        return $this->render('ordmission/impfichemiss.html.twig', [
            'Mission' => $Mission,
            'numcarte' => $result['numcarte'],
            'grade' => $result['grade'],
            'Nom_Prenom' => $result['Nom_Prenom']

        ]);
    }
}
