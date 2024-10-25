<?php

namespace OHMedia\VideoBundle\Controller;

use OHMedia\BackendBundle\Routing\Attribute\Admin;
use OHMedia\BootstrapBundle\Service\Paginator;
use OHMedia\UtilityBundle\Form\DeleteType;
use OHMedia\VideoBundle\Entity\Video;
use OHMedia\VideoBundle\Form\VideoType;
use OHMedia\VideoBundle\Repository\VideoRepository;
use OHMedia\VideoBundle\Security\Voter\VideoVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Admin]
class VideoController extends AbstractController
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    #[Route('/videos', name: 'video_index', methods: ['GET'])]
    public function index(Paginator $paginator): Response
    {
        $newVideo = new Video();

        $this->denyAccessUnlessGranted(
            VideoVoter::INDEX,
            $newVideo,
            'You cannot access the list of videos.'
        );

        $qb = $this->videoRepository->createQueryBuilder('v');
        $qb->orderBy('v.title', 'asc');

        return $this->render('@OHMediaVideo/video/video_index.html.twig', [
            'pagination' => $paginator->paginate($qb, 20),
            'new_video' => $newVideo,
            'attributes' => $this->getAttributes(),
        ]);
    }

    #[Route('/video/create', name: 'video_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $video = new Video();

        $this->denyAccessUnlessGranted(
            VideoVoter::CREATE,
            $video,
            'You cannot create a new video.'
        );

        $form = $this->createForm(VideoType::class, $video);

        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->videoRepository->save($video, true);

                $this->addFlash('notice', 'The video was created successfully.');

                return $this->redirectToRoute('video_index');
            }

            $this->addFlash('error', 'There are some errors in the form below.');
        }

        return $this->render('@OHMediaVideo/video/video_create.html.twig', [
            'form' => $form->createView(),
            'video' => $video,
        ]);
    }

    #[Route('/video/{id}/edit', name: 'video_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        #[MapEntity(id: 'id')] Video $video,
    ): Response {
        $this->denyAccessUnlessGranted(
            VideoVoter::EDIT,
            $video,
            'You cannot edit this video.'
        );

        $form = $this->createForm(VideoType::class, $video);

        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->videoRepository->save($video, true);

                $this->addFlash('notice', 'The video was updated successfully.');

                return $this->redirectToRoute('video_index');
            }

            $this->addFlash('error', 'There are some errors in the form below.');
        }

        return $this->render('@OHMediaVideo/video/video_edit.html.twig', [
            'form' => $form->createView(),
            'video' => $video,
        ]);
    }

    #[Route('/video/{id}/delete', name: 'video_delete', methods: ['GET', 'POST'])]
    public function delete(
        Request $request,
        #[MapEntity(id: 'id')] Video $video,
    ): Response {
        $this->denyAccessUnlessGranted(
            VideoVoter::DELETE,
            $video,
            'You cannot delete this video.'
        );

        $form = $this->createForm(DeleteType::class, null);

        $form->add('delete', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->videoRepository->remove($video, true);

                $this->addFlash('notice', 'The video was deleted successfully.');

                return $this->redirectToRoute('video_index');
            }

            $this->addFlash('error', 'There are some errors in the form below.');
        }

        return $this->render('@OHMediaVideo/video/video_delete.html.twig', [
            'form' => $form->createView(),
            'video' => $video,
        ]);
    }

    private function getAttributes(): array
    {
        return [
            'create' => VideoVoter::CREATE,
            'delete' => VideoVoter::DELETE,
            'edit' => VideoVoter::EDIT,
        ];
    }
}
