<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername(); 
        //dd($error->getMessage());
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
        ]);
    }

    /**
     * @Route("/logout",name="account_logout")
     */
    public function logout(): Response
    {
    }

    /**
     * @Route("/register",name="account_register")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    public function register(
        Request $request, 
        EntityManagerInterface $manager, 
        UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre Compte a bien été <strong>crée</strong> ! Vous pouvez maintenant vous connecter !"
            );
            return $this->redirectToRoute('account_login');
        }
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount(): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/account/profile",name="account_profile")
     * @IsGranted("ROLE_USER")
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Les données du profil ont été <strong>enregistrée</strong> avec succès!"
            );
        }
        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/account/password-update",name="account_password")
     * @IsGranted("ROLE_USER")
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function updatePassword(
        Request $request, 
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder): Response
    {
        $password = new PasswordUpdate();
        $form = $this->createForm(PasswordUpdateType::class, $password);
        $user = $this->getUser();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (false === password_verify($password->getOldPassword(), $user->getHash())) {
                $form->get('oldPassword')->addError(new FormError("Vérifier votre ancien mot de passe saisie !!!"));
            } else {
                $newHash = $encoder->encodePassword($user, $password->getNewPassword());
                $user->setHash($newHash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Votre Mot de passe a bien été <strong>modifié</strong> avec succès!"
                );
                return $this->redirectToRoute('homepage');
            }  
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
