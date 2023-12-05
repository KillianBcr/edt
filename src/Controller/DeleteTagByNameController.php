<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteTagByNameController extends AbstractController
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function __invoke(string $tagName)
    {
        $this->tagRepository->deleteByName($tagName);
    }
}
