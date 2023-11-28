<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AddTagToSubjectController extends AbstractController
{
    private SubjectRepository $subjectRepository;
    private TagRepository $tagRepository;

    public function __construct(SubjectRepository $subjectRepository, TagRepository $tagRepository)
    {
        $this->subjectRepository = $subjectRepository;
        $this->tagRepository = $tagRepository;
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        return new JsonResponse($request);

        $subject = $this->subjectRepository->find($id);
        $tag = $this->tagRepository->find($request->get('tag'));

        $subject->addTag($tag);
    }
}
