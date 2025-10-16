<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('api/user/')]
final class UserController extends AbstractController
{
    #[Route('me', name: 'user_me', methods: 'get')]
    public function private(): Response
    {

        $favoritList = [];
        foreach ($this->getUser()->getFavoriteLists() as $list) {
            if ($list->getIdsOfMuseums() != '') {
                $ids = explode(",", $list->getIdsOfMuseums());
                $favoritList += $ids;
            }
        }
        return $this->json([
            "id"=>$this->getUser()->getId(),
            "username"=>$this->getUser()->getUsername(),
            "localisation"=>$this->getUser()->getLocalisation(),
            "profileImage"=>$this->getUser()->getProfileImage(),
            "favoriteLists" => $favoritList
        ], 200, [], ["groups" => "profile"]);
    }

    #[Route('all/get', name: 'get_all',methods: "get")]
    public function index(UserRepository $repository): Response
    {
        return $this->json($repository->findAll(), 200, [], ['groups' => "profile"]);
    }
    #[Route('search', name: 'search',methods: "get")]
    public function search(UserRepository $repository, Request $request): Response
    {
        $search = $request->query->get("search", 50);

        return $this->json($repository->searchUser($search), 200, [], ['groups' => "profile"]);
    }

//    #[Route('add/image', name: 'add_profile_image', methods: ['POST'])]
//    public function addProfileImage(Request $request): Response
//    {
//        dd([
//            'all_files' => $request->files->all(),
//            'all_post' => $request->request->all(),
//            'headers' => $request->headers->all(),
//        ]);
//        dd($request->files->all());
//        $file = $request->files->get('profilImage');
//        dd($file);
//    }

}
