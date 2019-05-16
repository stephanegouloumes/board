<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Activity;
use App\Repository\UserRepository;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActivityController extends AbstractController
{
    /**
     * @Route("/board/{id}/activity", name="activity_index", methods={"GET"})
     *
     * @param Board $board
     * @param ActivityRepository $activityRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function index(Board $board, ActivityRepository $activityRepository, SerializerInterface $serializer): JsonResponse
    {
        $this->denyAccessUnlessGranted('view', $board);

        $data = $activityRepository->findByBoard($board, ['created_at' => 'DESC']);

        $dataJson = $serializer->serialize($data, 'json', ['groups' => ['activity']]);

        return new JsonResponse($dataJson, JsonResponse::HTTP_OK);
    }

    /**
     * @Route("board/{id}/activity", name="activity_new", methods={"POST"})
     *
     * @param Board $board
     * @param UserRepository $userRepository
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function new(Board $board, UserRepository $userRepository, Request $request, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $board);
        
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $user = $userRepository->find($parametersAsArray['user_id']);

        $activity = new Activity();
        $activity->setEntityType($parametersAsArray['entity_type'])
            ->setEntity($parametersAsArray['entity_id'])
            ->setAction($parametersAsArray['action'])
            ->setUser($user)
            ->setBoard($board);

        $errors = $validator->validate($activity);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->persist($activity);
        $entityManager->flush();

        $dataJson = $serializer->serialize($activity, 'json', ['groups' => ['activity']]);

        return new JsonResponse($dataJson, JsonResponse::HTTP_CREATED);
    }
}
