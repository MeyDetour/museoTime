<?php

namespace App\Service;

use App\Repository\MuseumImageRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MuseumsService
{
    private array $museums = [];

    public function __construct(
        private HttpClientInterface $client,
        private ImageService $imageService,
        private MuseumImageRepository $museumImageRepository
    ) {
        $this->fetchMuseums(50);
    }

    private function fetchMuseums(int $limit): void
    {

        $response = $this->client->request(
            'GET',
            "https://data.culture.gouv.fr/api/explore/v2.1/catalog/datasets/musees-de-france-base-museofile/records?limit=".($limit ? $limit : 20)
        );

        $data = $response->toArray()["results"];
        $this->museums = array_map(function ($datum) {
            $image = $this->museumImageRepository->findOneBy(["museumId" => $datum["identifiant"]]);
            $datum["image"] = $image    ? $this->imageService->getImageUrl($image->getImage(), "museum")
                : null;
            $datum["themes"] =      $datum["themes"]==""?null: explode(", ",$datum["themes"]);
            $datum["artiste"] =  $datum["artiste"]==""?null:  explode(", ",$datum["artiste"]);
            $datum["personnage_phare"] =   $datum["personnage_phare"]==""?null:  explode(", ",$datum["personnage_phare"]);
            return $datum;
        }, $data);
    }

    public function getMuseums(int $limit = 50): array
    {

        $this->fetchMuseums($limit>100 ? 100 : $limit);
        return $this->museums;
    }


    public function getMuseumWithId(string $id): ?array
    {
        if (!$id || trim($id) === '' || preg_match('/\s/', $id)) {
            return null;
        }

        foreach ($this->getMuseums(100) as $museum) {
            if ($museum["identifiant"] === $id) {
                return $museum;
            }
        }

        return null;
    }
}
