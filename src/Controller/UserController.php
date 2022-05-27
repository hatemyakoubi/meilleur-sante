<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\EditUserType;
use App\Form\ResetFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $passwordEncoder->encodePassword($user, $form->get("password")->getData());
            $user->setPassword($encoded);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
         $password = $user->getPassword();
        //  if ($this->isGranted('ROLE_ADMIN')) {    
         $form = $this->createForm(UserType::class, $user);    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd();
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Modification réussite');

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/change-password/{id}", name="app_change_password", methods={"GET","POST"})
     */
    public function ResetPassword(Request $request,UserRepository $userRepository,UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $entityManager,$id){
        
        
        $user= $userRepository->find($id);
        $form =$this->createForm(ResetFormType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            
            $plainPassword = $form["plainPassword"]->getData();
           
            if ($passwordEncoder->isPasswordValid($user, $plainPassword)) {
               
                $password = $form["password"]->getData();
                $encoded = $passwordEncoder->encodePassword($user, $form->get("password")->getData());
                $user->setPassword($encoded);
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash("success", "Votre mot de passe à été modifier avec succés");
                return $this->redirectToRoute("app_logout");
            }else {
                $this->addFlash("error","l\'ancien mot de passe est incorrecte");
                return $this->render('user/ResetPassword.html.twig',[
                    'form'=>$form->createView(),
                ]);
            }
        }

        return $this->render('user/ResetPassword.html.twig',[
            'form'=>$form->createView(),
        ]);

    }
}
