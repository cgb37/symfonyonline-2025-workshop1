<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ConferenceRepository;
use Twig\Environment;
use App\Entity\Conference;
use App\Repository\CommentRepository;

final class ConferenceController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        return $this->render('conference/index.html.twig', [
            'conferences' => $conferenceRepository->findAll(),
        ]);
    }

    #[Route('/conference/{slug}', name: 'conference')]
    public function show(Request $request, Environment $twig, CommentRepository $commentRepository, ConferenceRepository $conferenceRepository, string $slug): Response
    {
        $conference = $conferenceRepository->findOneBy(['slug' => $slug]);
        if (!$conference) {
            throw $this->createNotFoundException('Conference not found');
        }
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);
        $totalComments = $commentRepository->countByConference($conference);

        return new Response($twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::COMMENTS_PER_PAGE,
            'next' => $offset + CommentRepository::COMMENTS_PER_PAGE < $totalComments ? $offset + CommentRepository::COMMENTS_PER_PAGE : null,
            'hasPrevious' => $offset > 0,
            'hasNext' => $offset + CommentRepository::COMMENTS_PER_PAGE < $totalComments,
        ]));
    }
}
