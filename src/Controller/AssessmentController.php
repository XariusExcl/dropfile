<?php

namespace App\Controller;

use App\Entity\Assessment;
use App\Entity\Work;
use App\Repository\WorkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/assessments", name="api_assessments_")
 */
class AssessmentController extends AbstractController
{
    /**
     * @Route("/{assessment}", methods={"PUT"}, name="edit")
     * @param Assessment $assessment
     */
    public function editAssessment(Assessment $assessment, Request $request): JsonResponse
    {
        $admin = $this->getUser()->getAdmin();
        if ($admin != null)
        {
            $manager = $this->getDoctrine()->getManager();
            $data = json_decode($request->getContent(), true);

            $assessment->setTitle($data['title']);
            $assessment->setDescription($data['description']);
            $dueDate = \DateTime::createFromFormat('Y-m-d H:i:s.u', $data['dueDate']);
            $assessment->setDueDate($dueDate? $dueDate: null);

            $manager->flush();
            return new JsonResponse("Assessment edited!", 200);

        } else return new JsonResponse(['error' => "User is not an admin !"], 400);
    }

    /**
     * @Route("/{assessment}", methods={"DELETE"}, name="delete")
     * @param Assessment $assessment
     */
    public function deleteAssessment(Assessment $assessment): JsonResponse
    {
        $admin = $this->getUser()->getAdmin();
        if ($admin != null) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($assessment);
            $manager->flush();
            return new JsonResponse("Assessment deleted!", 200);

        } else return new JsonResponse(['error' => "User is not an admin !"], 400);
    }

    /**
     * @Route("/{assessment}/works", methods={"GET"}, name="works_get")
     * @param Assessment $assessment
     * Returns the works of x assessment
     */
    public function getWorks(Assessment $assessment, WorkRepository $workRepository): JsonResponse
    {
        $data['assessment'] = $assessment->serialize();
        $data['works'] = [];
        foreach ($workRepository->findBy(['assessment' => $assessment->getId()]) as $w) {
            array_push($data['works'], $w->serialize());
        }

        return new JsonResponse(json_encode($data));
    }

    /**
     * @Route("/{assessment}/works", methods={"POST"}, name="works_create")
     * @param Assessment $assessment
     */
    public function createWork(Assessment $assessment, Request $request): JsonResponse
    {
        // $admin = $this->getUser()->getAdmin();
        if (true) // TODO : Droits de création
        {
            $manager = $this->getDoctrine()->getManager();
            $data = json_decode($request->getContent(), true);

            $work = new Work();
            $work->setTitle($data['title']);
            $work->setDescription($data['description']);
            $work->setDescription($data['description']);
            $work->setDescription($data['is_public']);
            $work->setCreatedAt( new \DateTime());
            $work->setUpdatedAt( new \DateTime());

            $manager->persist($work);
            $manager->flush();
            return new JsonResponse("Work created!", 200);

        } else return new JsonResponse(['error' => ""], 400);
    }
}
