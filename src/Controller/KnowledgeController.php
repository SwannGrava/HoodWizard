<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Knowledge;
use App\Form\KnowledgeType;
use App\Repository\KnowledgeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/knowledge')]
// #[IsGranted('ROLE_USER')]
final class KnowledgeController extends AbstractController
{
    #[Route(name: 'app_knowledge_index', methods: ['GET'])]
    public function index(KnowledgeRepository $knowledgeRepository): Response
    {
        return $this->render('knowledge/index.html.twig', [
            'knowledge' => $knowledgeRepository->findAll(),
        ]);
    }
    //  la métode si dessous filtre les knowlege par id de l'entiter categorie et les affiche.
    #[Route("/categorie/{id}",name: 'app_knowledge_categorie', methods: ['GET'])] // trace la route + lui donne un nom + lui passe un parametre ( le ID de categorie)
    public function categorieFiltred(KnowledgeRepository $knowledgeRepository ,Categorie $categorie): Response // déclare la métode fait un param ( catégorie de id )
    {
        return $this->render('knowledge/index.html.twig', [
            'knowledge' => $knowledgeRepository->findBy(['categorie'=>$categorie]), // va chercher  la liste des knowledge qui on la catégorie passer en parametre (les param son dans les parentese de findBy )
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_knowledge_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $knowledge = new Knowledge();
    $form = $this->createForm(KnowledgeType::class, $knowledge);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Tu peux log ou afficher un message d'erreur ici
            }

            $knowledge->setImage($newFilename); // Assure-toi que ce champ existe bien
        }

        $entityManager->persist($knowledge);
        $entityManager->flush();

        return $this->redirectToRoute('app_knowledge_index');
    }

    return $this->render('knowledge/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_knowledge_show', methods: ['GET'])]
    public function show(Knowledge $knowledge): Response
    {
        return $this->render('knowledge/show.html.twig', [
            'knowledge' => $knowledge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_knowledge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Knowledge $knowledge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(KnowledgeType::class, $knowledge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_knowledge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('knowledge/edit.html.twig', [
            'knowledge' => $knowledge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_knowledge_delete', methods: ['POST'])]
    public function delete(Request $request, Knowledge $knowledge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$knowledge->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($knowledge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_knowledge_index', [], Response::HTTP_SEE_OTHER);
    }
}

