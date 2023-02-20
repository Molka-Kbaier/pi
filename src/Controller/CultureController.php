<?php

namespace App\Controller;

use App\Entity\Culture;
use App\Form\CultureType;
use App\Repository\CultureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/culture')]
class CultureController extends AbstractController
{
    #[Route('/', name: 'app_culture_index', methods: ['GET'])]
    public function index(CultureRepository $cultureRepository): Response
    {
        return $this->render('culture/index.html.twig', [
            'cultures' => $cultureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_culture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CultureRepository $cultureRepository): Response
    {
        $culture = new Culture();
        $form = $this->createForm(CultureType::class, $culture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $cultureRepository->save($culture, true);
            

            return $this->redirectToRoute('app_culture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('culture/new.html.twig', [
            'culture' => $culture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_culture_show', methods: ['GET'])]
    public function show(Culture $culture): Response
    {
        return $this->render('culture/show.html.twig', [
            'culture' => $culture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_culture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Culture $culture, CultureRepository $cultureRepository): Response
    {
        $form = $this->createForm(CultureType::class, $culture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cultureRepository->save($culture, true);

            

            return $this->redirectToRoute('app_culture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('culture/edit.html.twig', [
            'culture' => $culture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_culture_delete', methods: ['POST'])]
    public function delete(Request $request, Culture $culture, CultureRepository $cultureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$culture->getId(), $request->request->get('_token'))) {
            $cultureRepository->remove($culture, true);
        }
        

        return $this->redirectToRoute('app_culture_index', [], Response::HTTP_SEE_OTHER);
    }
}
