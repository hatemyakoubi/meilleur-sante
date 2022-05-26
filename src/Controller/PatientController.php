<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/patient")
 */
class PatientController extends AbstractController
{
    /**
     * @Route("/", name="app_patient_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $patients = $entityManager
            ->getRepository(Patient::class)
            ->findAll();

        return $this->render('patient/index.html.twig', [
            'patients' => $patients,
        ]);
    }

    /**
     * @Route("/new", name="app_patient_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
