<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\NbGroup;
use App\Entity\Subject;
use App\Entity\Tag;
use App\Repository\SemesterRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubjectController extends AbstractController
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    #[Route('/upload', name: 'app_upload')]
    public function upload(Request $request, SemesterRepository $semesterRepository): Response
    {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('file');

            if ($file && $file->isValid()) {
                $spreadsheet = IOFactory::load($file->getPathname());
                $entityManager = $this->registry->getManagerForClass(Subject::class);

                $groupRepository = $entityManager->getRepository(Group::class);
                $groupRepository->createQueryBuilder('g')
                    ->delete()
                    ->getQuery()
                    ->execute();

                $subjectRepository = $entityManager->getRepository(Subject::class);
                $subjectRepository->createQueryBuilder('s')
                    ->delete()
                    ->getQuery()
                    ->execute();

                $allData = [];

                $worksheets = iterator_to_array($spreadsheet->getWorksheetIterator());
                $lastWorksheet = end($worksheets);

                foreach ($worksheets as $worksheet) {
                    $sheetName = $worksheet->getTitle();

                    if ('Histogramme' === $sheetName) {
                        continue;
                    }

                    $data = $worksheet->toArray();

                    if ($data) {
                        if ($worksheet !== $lastWorksheet) {
                            for ($i = 1; $i < count($data); ++$i) {
                                if (!empty($data[$i][4])) {
                                    if (empty($data[$i][1])) {
                                        $data[$i][1] = $data[$i - 1][1];
                                    }

                                    if (empty($data[$i][2])) {
                                        $data[$i][2] = $data[$i - 1][2];
                                    }
                                }
                            }

                            $allData[$sheetName] = $data;
                        }
                        $firstWeek = null;
                        foreach ($data[0] as $value) {
                            if (is_numeric($value)) {
                                $firstWeek = $value;
                                break;
                            }
                        }

                        $lastWeek = null;
                        for ($i = count($data[0]) - 1; $i >= 0; --$i) {
                            if (is_numeric($data[0][$i])) {
                                $lastWeek = $data[0][$i];
                                break;
                            }
                        }
                        foreach ($data as $row) {
                            if (empty($row[4]) || str_starts_with($row[1], 'BUT')) {
                                continue;
                            }

                            if (empty($row[2])) {
                                continue;
                            }

                            $subjectCode = $row[1];
                            $name = $row[2];
                            $semesterNumber = (int) $row[1][2];
                            $tag = $row[7];

                            $semester = $semesterRepository->findOneBy(['name' => "Semestre $semesterNumber"]);

                            $existingSubject = $subjectRepository->findOneBy([
                                'subjectCode' => $subjectCode,
                                'name' => $name,
                            ]);

                            if ($existingSubject) {
                                $subject = $existingSubject;
                            } else {
                                $subject = new Subject();
                                $subject->setSubjectCode($subjectCode);
                                $subject->setName($name);
                                $subject->setFirstWeek($firstWeek);
                                $subject->setLastWeek($lastWeek);
                                $subject->setSemester($semester);
                                $entityManager->persist($subject);
                                $entityManager->flush();
                            }

                            $tabTags = explode(' / ', $tag);

                            foreach ($tabTags as $tag) {
                                $searchTag = $entityManager->getRepository(Tag::class)->findOneBy(['name' => $tag]);
                                if (null == $searchTag) {
                                    if ('' != $tag) {
                                        $tagCreate = new Tag();
                                        $tagCreate->setName($tag);
                                        $tagCreate->addSubject($subject);

                                        $entityManager->persist($tagCreate);
                                        $entityManager->flush();
                                    }
                                } else {
                                    $searchTag->addSubject($subject);
                                }
                            }

                            $subjectCode = $row[4];

                            $group = new Group();
                            $group->setType($subjectCode);

                            if ($subject) {
                                $group->setHourlyRate($row[5]);
                                $group->setSubject($subject);
                                $subject->addGroup($group);
                                $entityManager->persist($group);
                                $entityManager->flush();
                            } else {
                                $this->addFlash('error', 'No subject found for group: '.$subjectCode);
                            }

                            $nbGroup = new NbGroup();
                            $nbGroup->setNbGroup($row[3]);
                            $group->addNbGroup($nbGroup);
                            $entityManager->persist($nbGroup);
                            $entityManager->flush();
                        }
                    } else {
                        $this->addFlash('error', 'No data found in sheet: '.$sheetName);
                    }
                }
                $entityManager->flush();
            } else {
                $this->addFlash('error', 'Invalid file');
            }
        }

        return $this->render('subject/index.html.twig', [
            'data' => $allData ?? null,
        ]);
    }
}
