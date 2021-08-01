<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * 
     * @param AdRepository $repo
     * @return Response
     */
    public function index(AdRepository $repo): Response
    {
        return $this->render('ad/index.html.twig', [
            'ads' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $ad = new Ad();
        
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }
            
            $ad->setAuthor($this->getUser());
            
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );
            
            return $this->redirectToRoute('ads_show', [
                'slug' =>$ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ads/{slug}/update", name="ads_update")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier !")
     *
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            } 
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrées !"
            );
            
            return $this->redirectToRoute('ads_show', [
                'slug' =>$ad->getSlug()
            ]);
        }

        return $this->render('ad/update.html.twig', [
            'form' => $form->createView(),
            'ad'   => $ad
        ]);
    }

    /**
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @param Ad $ad  
     * @return Response 
     */
    public function show(Ad $ad): Response
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la supprimer !")
     *
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return Response
     * 
     */
    public function delete(Ad $ad, EntityManagerInterface $manager): Response
    {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimé !"
        );
        return $this->redirectToRoute('ads_index');
    }
}
