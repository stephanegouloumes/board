<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\CardList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        return new JsonResponse($dataJson);
    }

    /**
     * @Route("/board/{id}/column", name="column_new", methods={"POST"})
     */
    public function new(Board $board, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }

        $cardList = new CardList();
        $cardList->setTitle($parametersAsArray['title']);
        $cardList->setBoard($board);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cardList);
        $entityManager->flush();

        $dataJson = $serializer->serialize($cardList, 'json', ['groups' => ['column']]);
        // $dataJson = $serializer->serialize($cardList, 'json', ['attributes' => ['id', 'title', 'description']]);

        return new JsonResponse($dataJson);
    }

    /**
     * @Route("/column/{id}", name="column_delete", methods={"DELETE"})
     */
    public function delete(CardList $cardList): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cardList);
        $entityManager->flush();

        return new JsonResponse();
    }
}
