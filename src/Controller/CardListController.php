<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\CardList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CardListController extends AbstractController
{
    /**
     * @Route("/board/{id}/column", name="column_index", methods={"GET"})
     */
    public function index(Board $board, SerializerInterface $serializer): JsonResponse
    {
        $data = $this->getDoctrine()
            ->getRepository(CardList::class)
            ->findByBoard($board->id);

        $dataJson = $serializer->serialize($data, 'json', ['groups' => ['column']]);

        return new JsonResponse($dataJson, JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/board/{id}/column", name="column_new", methods={"POST"})
     */
    public function new(Board $board, Request $request, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $cardList = new CardList();
        $cardList->setTitle($parametersAsArray['title']);
        $cardList->setPosition($parametersAsArray['position']);
        $cardList->setBoard($board);

        $errors = $validator->validate($cardList);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cardList);
        $entityManager->flush();

        $dataJson = $serializer->serialize($cardList, 'json', ['groups' => ['column']]);
        // $dataJson = $serializer->serialize($cardList, 'json', ['attributes' => ['id', 'title', 'description']]);

        return new JsonResponse($dataJson, JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/board/{board_id}/column/{column_id}", name="column_edit", methods={"PATCH"})
     * 
     * @ParamConverter("board", options={"mapping": {"board_id": "id"}})
     * @ParamConverter("cardList", options={"mapping": {"column_id": "id"}})
     */
    public function edit(Board $board, CardList $cardList, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $cardList->setTitle($parametersAsArray['title']);

        $errors = $validator->validate($cardList);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/board/{id}/columns", name="column_edit_batch", methods={"PATCH"})
     */
    public function editBatch(Board $board, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($parametersAsArray as $cardListData) {
            $cardList = $this->getDoctrine()->getRepository(CardList::class)->find($cardListData['id']);
            $cardList->setPosition($cardListData['position']);

            $errors = $validator->validate($cardList);
            if (count($errors) > 0) {
                return new JsonResponse((string) $errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            $entityManager->persist($cardList);
        }

        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/column/{id}", name="column_delete", methods={"DELETE"})
     */
    public function delete(CardList $cardList): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cardList);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
