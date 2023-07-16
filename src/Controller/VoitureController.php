<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Entity\DemandeMission;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use App\Repository\DemandeMissionRepository;
use Proxies\__CG__\App\Entity\Voiture as EntityVoiture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VoitureController extends AbstractController
{
    #[Route('/voiture', name: 'app_voiture')]
    public function index(VoitureRepository $doctrine): Response
    {

        $Mission = $doctrine->findBy(['supprime' => false]);
        return $this->render('voiture/index.html.twig', [
            'Mission' => $Mission,
        ]);
    }
    #[IsGranted('ROLE_CHEF_DE_PARC')]
    #[Route('/voiture/create', name: 'Voiture_create')]
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $mission = new Voiture;
        $form = $this->createForm(VoitureType::class, $mission);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('voiture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $mission->setPhoto($newFilename);
            }

            $em->persist($mission);
            $em->flush();

            return $this->redirectToRoute('app_voiture');
        }

        return $this->renderForm('voiture/create.html.twig', ['formVoiture' => $form]);
    }
    #[IsGranted('ROLE_CHEF_DE_PARC')]
    #[Route('/voiture/{id<\d+>}/edit', name: 'Voiture_edit')]
    public function edit(Voiture $Mission, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(VoitureType::class, $Mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //$em->persist($Mission);
            $em->flush();
            return $this->redirectToRoute('app_voiture');
        }
        return $this->renderForm('voiture/edit.html.twig', ['formVoiture' => $form]);
    }
    #[IsGranted('ROLE_CHEF_DE_PARC')]
    #[Route('/voiture/{id<\d+>}/delete', name: 'voiture_delete')]
    public function delete(Voiture $Mission, EntityManagerInterface $entityManager): Response
    {

        $Mission->setSuprrimer(true);

        $entityManager->persist($Mission);
        $entityManager->flush();
        return $this->redirectToRoute('app_voiture');
    }
    #[IsGranted('ROLE_CHEF_DE_PARC')]
    #[Route('/voiture/{id<[0-9]+>}', name: 'show_Voiture')]
    public function show(ManagerRegistry $doctrine, $id)
    {
        $repository = $doctrine->getRepository(Voiture::class);
        $Mission = $repository->find($id);
        return $this->render('voiture/show.html.twig', [
            'Mission' => $Mission,
        ]);
    }
    #[IsGranted('ROLE_CHEF_DE_PARC')]
    #[Route('/voiture/missAff', name: 'app_voit_miss')]
    public function Mission(DemandeMissionRepository $doctrine): Response
    {

        $Mission = $doctrine->findBy(['supprime' => false, 'voiture' => null, 'employe' => null]);
        return $this->render('voiture/Affecter.html.twig', [
            'Mission' => $Mission,
        ]);
    }
    #[IsGranted('ROLE_CHEF_DE_PARC')]
    #[Route('/voiture/stats', name: 'voit_stats')]
    public function MissStats(DemandeMissionRepository $voit)
    {

        $voiturest = $voit->findByVoiture();


        $models = [];
        $voitcount = [];
        if ($voiturest) {

            foreach ($voiturest as $miss) {

                $models[] = $miss['model'];
                $voitcount[] = $miss['count'];
            }
        }

        return $this->render('voiture/statschef.html.twig', [
            'models' => json_encode($models),
            'voitcount' => json_encode($voitcount),

        ]);
    }
}
