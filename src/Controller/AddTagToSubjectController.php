<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use App\Repository\TagRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function __invoke(int $id, Request $request)
    {
        $subject = $this->subjectRepository->find($id);
        $tag = $this->tagRepository->find(json_decode($request->getContent())->tag);

        $subject->addTag($tag);

        return $subject;
    }
}
