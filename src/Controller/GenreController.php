<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Serie;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use App\Repository\SerieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class GenreController extends AbstractController
{
    #[Route('/', name: 'app_genre_index', methods: ['GET'])]
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('genre/index.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }

    #[Route('/genre/{id}', name: 'showseriesbygenre')]
    public function showseriesbygenre(ManagerRegistry $doctrine, int $id): Response
    {
        $evt = $doctrine->getRepository(Genre::class)->find($id);
//        $series = $doctrine->getRepository(Serie::class)->findBy('genre' => $evt);


        return $this->render('home/showseries.html.twig', [
            'genre' => $evt,
        ]);
    }
    #[Route('/episodes/{id}', name: 'showepisodesbyserie')]
    public function showepisodesbyseries(ManagerRegistry $doctrine, int $id): Response
    {
        $evt = $doctrine->getRepository(Serie::class)->find($id);
//        $series = $doctrine->getRepository(Serie::class)->findBy('genre' => $evt);


        return $this->render('home/showepisodes.html.twig', [
            'series' => $evt,
        ]);
    }

    #[Route('/new', name: 'app_genre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GenreRepository $genreRepository): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genreRepository->save($genre, true);

            return $this->redirectToRoute('app_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_genre_show', methods: ['GET'])]
    public function show(Genre $genre): Response
    {
        return $this->render('genre/show.html.twig', [
            'genre' => $genre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_genre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Genre $genre, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genreRepository->save($genre, true);

            return $this->redirectToRoute('app_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_genre_delete', methods: ['POST'])]
    public function delete(Request $request, Genre $genre, GenreRepository $genreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $genreRepository->remove($genre, true);
        }

        return $this->redirectToRoute('app_genre_index', [], Response::HTTP_SEE_OTHER);
    }
}
