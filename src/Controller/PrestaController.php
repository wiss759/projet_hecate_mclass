<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserOpenHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestaController extends AbstractController
{
    #[Route('/presta/{user}', name: 'app_presta')]
    public function index(User $user): Response
    {
        return $this->render('presta/index.html.twig', [
            'presta' => $user,
        ]);
    }

    #[Route('/prestahours', name: 'app_presta_hours')]
    public function prestahours(Request $request, UserOpenHoursRepository $userOpenHoursRepository): JsonResponse
    {
        $data = json_decode($request->getContent());
        
        $list = $userOpenHoursRepository->getOpenHoursByDayAndUser($data->userId, $data->date);

        $tab = [];
        foreach($list as $row){
            $tab[] = [
                'start_hours' => $row->getOpenHours()->getStartHours()->format('H:i'),
                'end_hours' => $row->getOpenHours()->getEndHours()->format('H:i')
            ];
        }

        return new JsonResponse($tab);
    }
}
