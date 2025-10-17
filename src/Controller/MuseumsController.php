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


    #[Route('/museum/apply/filters', name: 'apply_filter', methods: ['POST'])]
    public function applyFilter(Request $request, MuseumsService $museumsService): Response
    {
        $data = json_decode($request->getContent(), true);
        $allMuseums = $museumsService->getMuseums(1000); // récupère tous les musées
        $filtered = [];


        // on verifie que le musée respecte les contraintes provoqués par les params
        foreach ($allMuseums as $museum) {
            $match = true;

            if (!empty($data["themes"]) && $museum["themes"]) {
                if (count(array_intersect($data["themes"], $museum["themes"])) === 0) {
                    $match = false;
                }
            }

            if (!empty($data["domaine_thematique"]) && $museum["domaine_thematique"]) {

                if (count(array_intersect($data["domaine_thematique"], $museum["domaine_thematique"])) === 0) {
                    $match = false;
                }
            }

            if (!empty($data["departement"]) && $data["departement"]) {
                if (!in_array($museum["departement"], $data["departement"])) {
                    $match = false;
                }
            }

            if (!empty($data["annee_creation"]) && $data["annee_creation"]) {
                if (!in_array($museum["annee_creation"], $data["annee_creation"])) {
                    $match = false;
                }
            }

            if ($match) {
                $filtered[] = $museum;
            }
        }
        $data = array_map(function ($museum) {

            return [
                "id" => $museum["identifiant"],
                "nom_officiel" => $museum["nom_officiel"],
                "image" => $museum["image"] ?? null,
            ];
        }, $filtered);
        return $this->json($data, 200);
    }


    #[Route('/museum/get/filters', name: 'get_filter')]
    public function getFilters(MuseumsService $museumsService): Response
    {
        $filters = [
            "themes" => ["Autres"],
            "domaine_thematique" => ["Autres"],
            "departement" => ["Autres"],
            "annee_creation" => ["Autres"],
        ];
        // pour chaque musée on récupère tous ses theme, et on les ajoute dans la liste si il n'y sont pas déja
        foreach ($museumsService->getMuseums(100) as $museum) {
            // Domaine thématique
            if ($museum["domaine_thematique"]) {
                foreach ($museum["domaine_thematique"] as $item) {
                    if (!in_array($item, $filters["domaine_thematique"])) {
                        $filters["domaine_thematique"][] = $item;
                    }
                }
            }


            // Themes
            if ($museum["themes"]) {
                foreach ($museum["themes"] as $item) {
                    $item = trim($item); // pour enlever les espaces éventuels
                    if (!in_array($item, $filters["themes"])) {
                        $filters["themes"][] = $item;
                    }
                }
            }


            // Departement
            if ($museum["departement"]) {
                if (!in_array($museum["departement"], $filters["departement"])) {
                    $filters["departement"][] = $museum["departement"];
                }
            }

            if ($museum["annee_creation"]) {
                // Année de création
                if (!in_array($museum["annee_creation"], $filters["annee_creation"])) {
                    $filters["annee_creation"][] = $museum["annee_creation"];
                }
            }

        }

        return $this->json($filters, 200);
    }


    #[Route('/museums', name: 'app_home')]
    public function getMuseums(Request $request): Response
    {
        $limit = (int)$request->query->get("limit", 50);

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

        $museum["liked"] = false;

        return $this->json($museum, 200);
    }



    // unused
    #[Route('api/museum/{id}', name: 'get_museum_with_user')]
    public function getMuseumWithUser(MuseumsService $museumsService, $id): Response
    {
        $museum = $museumsService->getMuseumWithId($id);

        $museum["liked"] = false;
        if ($this->getUser()) {
            $favoritsList = $this->getUser()->getFavoriteLists();

            foreach ($favoritsList as $list) {
                $listExploded = explode(",", $list->getIdsOfMuseums());

                if (in_array($id, $listExploded)) {
                    $museum["liked"] = true;
                }
            }

        }
        return $this->json($museum, 200);
    }

}
