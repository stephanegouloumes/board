<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\CardList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CardController extends AbstractController
{
    /**
     * @Route("/column/{id}/card", name="card_new", methods={"POST"})
     */
    public function new(CardList $cardList, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $card = new Card();
        $card->setTitle($parametersAsArray['title']);
        $card->setCardList($cardList);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($card);
        $entityManager->flush();

        $dataJson = $serializer->serialize($card, 'json', ['groups' => ['card']]);

        return new JsonResponse($dataJson);
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

        return new JsonResponse();
    }
}
