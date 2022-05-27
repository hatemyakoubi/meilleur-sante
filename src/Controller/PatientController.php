<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\User;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/patient")
 */
class PatientController extends AbstractController
{
    /**
     * @Route("/", name="app_patient_index", methods={"GET"})
     */
    public function index(PatientRepository $patientRepository,UserRepository $userRepository): Response
    {
        $userConnecter = $this->getUser();
       $id= $this->getUser()->getId();
      // dd($userConnecter);
        $patients=[];
        // if ($userConnecter->getRoles()=="ROLE_ADMIN") {
            if ($this->isGranted('ROLE_ADMIN')){
            $patients = $patientRepository->findAll();
        }
        else{
            $user = $userRepository->find($id);
            foreach ($user->getPatients() as $p  ) {
                $patients[]= [
                    'id'=> $p->getId(),
                    'identificaion'=> $p->getIdentificaion(),
                    'nomComplet'=> $p->getNomComplet(),
                    'sexe'=> $p->getSexe(),
                    'age'=> $p->getAge(),
                    'localisation'=> $p->getLocalisation(),
                ];
            }
           
        }
        return $this->render('patient/index.html.twig', [
            'patients' => $patients,
        ]);
    }

    /**
     * @Route("/new", name="app_patient_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setUser($this->getUser());
            $entityManager->persist($patient);
            $entityManager->flush();

            return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('patient/new.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_patient_show", methods={"GET"})
     */
    public function show(Patient $patient): Response
    {
        $temperature = $patient->getTemperature();
        $IntervalleTemp=[];
        for ($i=0; $i < $temperature; $i++) { 
            $IntervalleTemp[]=$i;
           
        }
        $oxygene = $patient->getOxygene();
        $IntervalleOxygene=[];
        for ($i=0; $i < $oxygene; $i++) { 
            $IntervalleOxygene[]=$i;
           
        }
       // dd($IntervalleTemp);
        return $this->render('patient/show.html.twig', [
            'patient' => $patient,
            'IntervalleTemp' =>json_encode($IntervalleTemp),
            'IntervalleOxygene' =>json_encode($IntervalleOxygene),
            'oxygene' =>$oxygene,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_patient_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Patient $patient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setUser($this->getUser());
            $entityManager->persist($patient);
            $entityManager->flush();

            return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('patient/edit.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_patient_delete", methods={"POST"})
     */
    public function delete(Request $request, Patient $patient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$patient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($patient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
    }
}
