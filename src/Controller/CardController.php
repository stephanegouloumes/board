<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Board;
use App\Entity\CardList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\CardRepository;
use App\Repository\CardListRepository;

class CardController extends AbstractController
{
    /**
     * @Route("board/{board_id}/column/{column_id}/card", name="card_new", methods={"POST"})
     * 
     * @ParamConverter("board", options={"mapping": {"board_id": "id"}})
     * @ParamConverter("cardList", options={"mapping": {"column_id": "id"}})
     */
    public function new(Board $board, CardList $cardList, Request $request, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $board);

        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $card = new Card();
        $card->setTitle($parametersAsArray['title'])
            ->setPosition($parametersAsArray['position'])
            ->setCardList($cardList);

        $errors = $validator->validate($card);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($card);
        $entityManager->flush();

        $dataJson = $serializer->serialize($card, 'json', ['groups' => ['card']]);

        return new JsonResponse($dataJson, JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("board/{board_id}/column/{column_id}/card/{card_id}", name="card_edit", methods={"PATCH"})
     * 
     * @ParamConverter("board", options={"mapping": {"board_id": "id"}})
     * @ParamConverter("cardList", options={"mapping": {"column_id": "id"}})
     * @ParamConverter("card", options={"mapping": {"card_id": "id"}})
     */
    public function edit(Board $board, CardList $cardList, Card $card, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $board);

        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $card->setTitle($parametersAsArray['title'])
            ->setDescription($parametersAsArray['description']);

        $errors = $validator->validate($card);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/board/{id}/cards", name="card_edit_batch", methods={"PATCH"})
     */
    public function editBatch(Board $board, CardRepository $cardRepository, CardListRepository $cardListRepository, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $board);

        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($parametersAsArray as $cardData) {
            $card = $cardRepository->find($cardData['id']);
            $card->setPosition($cardData['position']);

            if (isset($cardData['card_list_id'])) {
                $card->setCardList($cardListRepository->find( $cardData['card_list_id']));
            }

            $errors = $validator->validate($card);
            if (count($errors) > 0) {
                return new JsonResponse((string)$errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            $entityManager->persist($card);
        }

        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("board/{board_id}/column/{column_id}/card/{card_id}", name="card_delete", methods={"DELETE"})
     * 
     * @ParamConverter("board", options={"mapping": {"board_id": "id"}})
     * @ParamConverter("cardList", options={"mapping": {"column_id": "id"}})
     * @ParamConverter("card", options={"mapping": {"card_id": "id"}})
     */
    public function delete(Board $board, CardList $cardList, Card $card): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $board);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($card);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
