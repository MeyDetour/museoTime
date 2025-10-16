<?php

namespace App\Controller;

use App\Entity\Share;
use App\Entity\User;
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
use Symfony\Contracts\HttpClient\HttpClientInterface ;

#[Route('api/share/')]
final class ShareMuseumController extends AbstractController
{
    private MuseumsService $museumsService;

    public function __Construct(MuseumsService $museumsService)
    {
        $this->museumsService = $museumsService;
    }


    // Get al museum shared
    #[Route('get', name: 'get_museums_shared',methods: "GET")]
    public function getShared( ): Response
    {
        $shares = $this->getUser()->getSharesReceiver();

        $data = array_map(function($share) {
            $museum =  $this->museumsService->getMuseumWithId($share->getMuseumId());
            return [
                'id' => $share->getId(),
                'museum' =>[
                    "id"=>$museum["identifiant"],
                    "nom_officiel"=>$museum["nom_officiel"],
                    "image"=>$museum["image"]??null,
                ],
                'createdAt' => $share->getCreatedAt(),
                'sender' => $share->getSender()->getUsername(),
            ];
        }, $shares->toArray());

        return $this->json($data,200);
    }

        // User share one museum with another profile
    #[Route('museum/to/user/{id}', name: 'share_one_museum',methods: "POST")]
    public function share( User $receiver,EntityManagerInterface $manager,UserRepository $userRepository,Request $request ,SerializerInterface $serializer ): Response
    {
        if (!$receiver){
            return  $this->json(["message"=>"User not found !"],404);
        }

        // create Share element
        $share = $serializer->deserialize($request->getContent(),Share::class,"json");
        //verify Museum id
        $museum = $this->museumsService->getMuseumWithId($share->getMuseumId());
        if (!$museum) {
            return $this->json(["message" => "Please enter valid museum id"], 409);
        }
        $share->setCreatedAt(new \DateTimeImmutable());
        $share->setSender($this->getUser());
        $share->setReceiver($receiver);


        $manager->persist($share);
        $manager->flush();
        return  $this->json(["message"=>"ok"],201);
    }

    #[Route('delete/{id}', name: 'delete_sharing',methods: "DELETE")]
    public function deleteSharing(EntityManagerInterface $manager, Share $share ): Response
    {
        if(!$share){
            return $this->json(["message"=>"Share not found"],404);
        }

        if ($share->getReceiver() !== $this->getUser()){
            return $this->json(["message"=>"Not allowed"],401);
        }
        $manager->remove($share);
        $manager->flush();
        return $this->json(["message"=>"ok"],200);

    }
}
