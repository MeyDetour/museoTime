<?php

namespace App\Controller;

use App\Repository\MuseumImageRepository;
use App\Service\ImageService;
use App\Service\MuseumsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MuseumsController extends AbstractController
{
    private MuseumsService $museumsService;

    public function __Construct(MuseumsService $museumsService)
    {
        $this->museumsService = $museumsService;
    }

    #[Route('/museums', name: 'app_home')]
    public function getMuseums(Request $request): Response
    {
        $limit = (int) $request->query->get("limit", 50);

        $data = array_map(function ($museum) {

            return [
                "id" => $museum["identifiant"],
                "nom_officiel" => $museum["nom_officiel"],
                "image" => $museum["image"] ?? null,
            ];
        }, $this->museumsService->getMuseums($limit));
        return $this->json($data, 200);
    }

    #[Route('/museum/{id}', name: 'get_museum')]
    public function getMuseum(MuseumsService $museumsService, $id): Response
    {
        $museum = $museumsService->getMuseumWithId($id);

        $museum["liked"]=false;

        return $this->json($museum, 200);
    }

    #[Route('api/museum/{id}', name: 'get_museum_with_user')]
    public function getMuseumWithUser(MuseumsService $museumsService, $id): Response
    {
        $museum = $museumsService->getMuseumWithId($id);

        $museum["liked"]=false;
        if ($this->getUser()){
            $favoritsList = $this->getUser()->getFavoriteLists();

            foreach ($favoritsList as $list){
               $listExploded = explode(",",$list->getIdsOfMuseums());

                if (in_array ($id ,$listExploded)){
                    $museum["liked"]=true;
                }
            }

        }
        return $this->json($museum, 200);
    }

}
