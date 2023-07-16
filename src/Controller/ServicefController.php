<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Employe;
use App\Entity\DemandeMission;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DemandeMissionRepository;
use App\Repository\EmployeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ServicefController extends AbstractController
{
    #[IsGranted('ROLE_FINANCIER')]
    #[Route('/payer', name: 'payer-miss')]
    public function IndexPayer(DemandeMissionRepository $doctrine, Request $request, EntityManagerInterface $entityManager, EmployeRepository $emp): Response
    {

        $Mission = $doctrine->findBy(['valider' => true, 'ordvalid' => true, 'payer' => false]);
        $employe = $emp->findAll();
        return $this->render('ServiceFinancier/serfin.html.twig', [
            'Mission' => $Mission,
            'employe' => $employe
        ]);
    }
    #[IsGranted('ROLE_FINANCIER')]
    #[Route('/indsf', name: 'indsf')]
    public function IndexSf(DemandeMissionRepository $doctrine, Request $request, EntityManagerInterface $entityManager, EmployeRepository $emp): Response
    {

        $Mission = $doctrine->findBy(['valider' => true, 'ordvalid' => true]);
        $employe = $emp->findAll();
        return $this->render('ServiceFinancier/indexsf.html.twig', [
            'Mission' => $Mission,
            'employe' => $employe
        ]);
    }
    #[IsGranted('ROLE_FINANCIER')]
    #[Route('/payer/valider', name: 'payer_valid')]
    public function Missvalid(EntityManagerInterface $entityManager, Request $request): Response
    {

        if ($request->isMethod('POST')) {
            $formData = $request->request->all();

            $resultat = isset($formData['input']) ? $formData['input'] : array();

            if ($resultat != null) {
                foreach ($resultat as $missionId) {
                    $mission = $entityManager->getRepository(DemandeMission::class)->find($missionId);
                    $mission->setPayer(true);
                    $entityManager->persist($mission);
                }
                $entityManager->flush();
            }
        }
        // Retrieve the selected Missions from the database


        // Mark each Mission as paid and persist the changes to the database

        // $Mission->setPayer(true);

        // $entityManager->persist($Mission);
        // $entityManager->flush();

        return $this->redirectToRoute('payer-miss');
    }
    #[IsGranted('ROLE_FINANCIER')]
    #[Route('/payer/fac', name: 'misspayer-miss')]
    public function IndexMissPayer(DemandeMissionRepository $doctrine, EmployeRepository $emp): Response
    {

        $Mission = $doctrine->findBy(['valider' => true, 'ordvalid' => true, 'payer' => true]);
        $employe = $emp->findAll();
        return $this->render('ServiceFinancier/facture.html.twig', [
            'Mission' => $Mission,
            'employe' => $employe
        ]);
    }
    #[IsGranted('ROLE_FINANCIER')]
    #[Route('/fact/{id<\d+>}/valider/', name: 'fact_payer')]
    public function MissFactDem(DemandeMission $Mission, EmployeRepository $employe): Response
    {
        $user = $Mission->getUser();
        $employeUser = $employe->findAll();

        $dateD = $Mission->getDateDebut();
        $dateF = $Mission->getDateFin();
        $interval = date_diff($dateF, $dateD);
        $periode = intval($interval->format('%a')) + 1;








        $this->addFlash('success', 'Demande ' . $Mission->getNumMiss() . ' validée.');
        return $this->render('ServiceFinancier/impfac.html.twig', [

            'periode' => $periode,

            'Mission' => $Mission,
            'employeUser' => $employeUser

        ]);
    }
    #[IsGranted('ROLE_FINANCIER')]
    #[Route('/fact/{id}/emp', name: 'fact_payeremp')]
    public function MissFactEmp(DemandeMission $Mission, EntityManagerInterface $entityManager): Response
    {


        $employe = $Mission->getEmployes();
        $dateD = $Mission->getDateDebut();
        $dateF = $Mission->getDateFin();
        $interval = date_diff($dateF, $dateD);
        $periode = intval($interval->format('%a')) + 1;
        $salaries = [];
        foreach ($employe as $e) {
            if ($e->getProfession() == 'Administrateur en chef') {
                $salaire = $e->getProfession()->getMontant();
                $salaries[$e->getId()] = $salaire * $periode;
            } else if ($e->getProfession() == 'ingénieur en chef') {
                $salaire = $e->getProfession()->getMontant();
                $salaries[$e->getId()] = $salaire * $periode;
            } else if ($e->getProfession() == 'ingénieur principale') {
                $salaire = $e->getProfession()->getMontant();
                $salaries[$e->getId()] = $salaire * $periode;
            }
        }


        $this->addFlash('success', 'Demande ' . $Mission->getNumMiss() . ' validée.');
        return $this->render('ServiceFinancier/impfacemp.html.twig', [
            'salaries' => $salaries,
            'periode' => $periode,
            'Mission' => $Mission,
            'employe' => $employe
        ]);
    }
    #[IsGranted('ROLE_FINANCIER')]
    #[Route('/mission/stats/fn', name: 'fin_stats')]
    public function SgStats(DemandeMissionRepository $missrepo)
    {

        $mission = $missrepo->findByPayer();
        $datesfn = [];
        $misscountfn = [];

        foreach ($mission as $miss) {

            $datesfn[] = $miss['Month'];
            $misscountfn[] = $miss['count'];
        }
        return $this->render('ServiceFinancier/fnstats.html.twig', [
            'datesfn' => json_encode($datesfn),
            'misscountfn' => json_encode($misscountfn)
        ]);
    }
}
