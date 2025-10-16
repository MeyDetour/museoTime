<?php

namespace App\Controller;

use App\Entity\FavoriteList;
use App\Repository\FavoriteListRepository;
use App\Repository\MuseumImageRepository;
use App\Repository\UserRepository;
use App\Service\ImageService;
use App\Service\MuseumsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('api/favorite/list')]
final class FavoriteListController extends AbstractController
{
    private MuseumsService $museumService;

    public function __Construct(MuseumsService $museumService)
    {

        $this->museumService = $museumService;
    }


    #[Route('/get/all', name: 'get_all_lists', methods: ["GET"])]
    public function getAll(Request $request, EntityManagerInterface $manager, FavoriteListRepository $repository): Response
    {
        $data = array_map(function ($favoriteList) {
            $museums = array_map(function ($museumId) {
                $museum = $this->museumService->getMuseumWithId($museumId);
                if (!$museum) return null; // musée introuvable → on ignore
                return [
                    'id' => $museum['identifiant'],
                    'nom_officiel' => $museum['nom_officiel'],
                    'image' => $museum['image'] ?? null,
                ];
            }, explode(",", $favoriteList->getIdsOfMuseums()));

            // on retire les musées null
            $museums = array_filter($museums);

            return [
                'id' => $favoriteList->getId(),
                'name' => $favoriteList->getName(),
                'museums' => array_values($museums), // réindexe les clés
            ];
        }, $this->getUser()->getFavoriteLists()->toArray());

        return $this->json($data, 200, [], ['groups' => 'favoriteList']);

    }

    #[
        Route('/create', name: 'create_favorite_list', methods: "POST")]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, UserRepository $repository): Response
    {

        $favoriteList = $serializer->deserialize($request->getContent(), FavoriteList::class, "json");
        $favoriteList->setCreatedBy($this->getUser());

        if (!$favoriteList->getName() || trim($favoriteList->getName()) === '' || preg_match('/\s/', $favoriteList->getName())) {
            return $this->json(["message" => "Please enter valid name"]);
        }
        $manager->persist($favoriteList);
        $manager->flush();
        return $this->json($favoriteList, 200, [], ["groups" => "favoriteList"]);
    }

    #[Route('/edit/{id}', name: 'edit_favorite_list', methods: "PUT")]
    public function edit(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, UserRepository $repository, FavoriteList $list): Response
    {

        $listEdited = $serializer->deserialize($request->getContent(), FavoriteList::class, "json");
        if ($list->getCreatedBy() !== $this->getUser()) {
            return $this->json(["message" => "Unauthorized"], 401);
        }

        if (!$listEdited->getName() || trim($listEdited->getName()) === '' || preg_match('/\s/', $listEdited->getName())) {
            return $this->json(["message" => "Please enter valid name"]);
        }

        $list->setName($listEdited->getName());
        $manager->persist($list);
        $manager->flush();
        return $this->json(["message"=>"ok"], 200, [], ["groups" => "favoriteList"]);
    }

    // Remove List
    #[Route('/remove/{id}', name: 'remove_list', methods: ["DELETE"])]
    public function removeList(FavoriteList $favoriteList, EntityManagerInterface $manager): Response
    {
        if ($favoriteList->getCreatedBy() !== $this->getUser()) {
            return $this->json(["message" => "Unauthorized"], 401);
        }
        if (!$favoriteList) {
            return $this->json(["message" => "Liste introuvable"], 404);
        }
        $manager->remove($favoriteList);
        $manager->flush();
        return $this->json(["message" => "ok"], 200);
    }


    // Add Museum to specific list
    #[Route('/add/in/{id}', name: 'add_to_favorite_list', methods: "PUT")]
    public function add(Request $request, EntityManagerInterface $manager, FavoriteListRepository $repository, FavoriteList $list): Response
    {
        if (!$list) {
            return $this->json(["message" => "Please enter valid favorite list id"]);
        }
        if ($list->getCreatedBy() !== $this->getUser()) {
            return $this->json(["message" => "Unauthorized"], 401);
        }

        $params = json_decode($request->getContent(), true);

        $museumId = $params["museumId"];
        $museum = $this->museumService->getMuseumWithId($museumId);
        if (!$museum) {
            return $this->json(["message" => "Please enter valid museum id"], 409);
        }

        if ($list->getIdsOfMuseums()) {
            $arrayOfIds = explode(",", $list->getIdsOfMuseums());
            if (!in_array($museumId, $arrayOfIds)) {
                array_push($arrayOfIds, $museumId);
                $list->setIdsOfMuseums(implode(",", $arrayOfIds));
            }
        } else {
            $list->setIdsOfMuseums(strval($museumId));
        }

        $manager->persist($list);
        $manager->flush();
        return $this->json(["message" => "ok"], 200, [], ["groups" => "favoriteList"]);
    }


    // Remove Museum from specific list
    #[Route('/remove/in/{id}', name: 'remove_to_favorite_list', methods: "DELETE")]
    public function remove(FavoriteList $list, Request $request, EntityManagerInterface $manager, FavoriteListRepository $repository): Response
    {
        if (!$list) {
            return $this->json(["message" => "Please enter valid favorite list id"]);
        }
        if ($list->getCreatedBy() !== $this->getUser()) {
            return $this->json(["message" => "Unauthorized"], 401);
        }
        $params = json_decode($request->getContent(), true);

        $museumId = $params["museumId"];
        $museum = $this->museumService->getMuseumWithId($museumId);
        if (!$museum) {
            return $this->json(["message" => "Please enter valid museum id"], 409);
        }

        $arrayOfIds = explode(",", $list->getIdsOfMuseums());
        if (($key = array_search($museumId, $arrayOfIds)) !== false) {
            unset($arrayOfIds[$key]);
        }
        $list->setIdsOfMuseums(implode(",", $arrayOfIds));
        $manager->persist($list);
        $manager->flush();
        return $this->json(["message"=>"ok"], 200, [], ["groups" => "favoriteList"]);
    }


    // Remove like on Museum without list id
    #[Route('/remove/one/item', name: 'remove_item_in_list', methods: "DELETE")]
    public function removeEletWithoutListId(Request $request, EntityManagerInterface $manager, FavoriteListRepository $repository): Response
    {

        $params = json_decode($request->getContent(), true);

        $museumId = $params["museumId"];
        $museum = $this->museumService->getMuseumWithId($museumId);
        if (!$museum) {
            return $this->json(["message" => "Please enter valid museum id"], 409);
        }
        foreach ($this->getUser()->getFavoriteLists() as $list) {
            $arrayOfIds = explode(",", $list->getIdsOfMuseums());
            if (($key = array_search($museumId, $arrayOfIds)) !== false) {
                unset($arrayOfIds[$key]);
            }
            $list->setIdsOfMuseums(implode(",", $arrayOfIds));
            $manager->persist($list);
            $manager->flush();
        }


        return $this->json(["message" => "ok"], 200, [], ["groups" => "favoriteList"]);
    }





//    #[Route('/get/all/inAdminMode', name: 'get_all_lists_DEBUG')]
//    public function getAllAdmin(Request $request, EntityManagerInterface $manager, FavoriteListRepository $repository): Response
//    {
//        return $this->json($repository->findAll(), 200, [], ["groups" => "favoriteList"]);
//    }


}
