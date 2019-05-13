<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\CardList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CardController extends AbstractController
{
    /**
     * @Route("/column/{id}/card", name="card_new", methods={"POST"})
     */
    public function new(CardList $cardList, Request $request, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $card = new Card();
        $card->setTitle($parametersAsArray['title']);
        $card->setCardList($cardList);

        $errors = $validator->validate($card);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors, 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($card);
        $entityManager->flush();

        $dataJson = $serializer->serialize($card, 'json', ['groups' => ['card']]);

        return new JsonResponse($dataJson, 201);
    }

    /**
     * @Route("/column/{column_id}/card/{card_id}", name="card_edit", methods={"PUT"})
     * 
     * @ParamConverter("cardList", options={"mapping": {"column_id": "id"}})
     * @ParamConverter("card", options={"mapping": {"card_id": "id"}})
     */
    public function edit(CardList $cardList, Card $card, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $card->setTitle($parametersAsArray['title']);
        $card->setDescription($parametersAsArray['description']);

        $errors = $validator->validate($card);
        if (count($errors) > 0) {
            return new JsonResponse((string)$errors, 422);
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/column/{column_id}/card/{card_id}", name="card_delete", methods={"DELETE"})
     * 
     * @ParamConverter("cardList", options={"mapping": {"column_id": "id"}})
     * @ParamConverter("card", options={"mapping": {"card_id": "id"}})
     */
    public function delete(CardList $cardList, Card $card): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($card);
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}
