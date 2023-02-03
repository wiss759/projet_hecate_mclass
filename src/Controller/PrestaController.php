<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\OpenHours;
use App\Entity\User;
use App\Repository\UserOpenHoursRepository;
use Fpdf\Fpdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestaController extends AbstractController
{
    #[Route('/presta/{user}/{category}', name: 'app_presta')]
    public function index(User $user, Category $category): Response
    {
        return $this->render('presta/index.html.twig', [
            'presta' => $user,
            'category' => $category
        ]);
    }

    #[Route('/prestahours', name: 'app_presta_hours')]
    public function prestahours(Request $request, UserOpenHoursRepository $userOpenHoursRepository): JsonResponse
    {
        $data = json_decode($request->getContent());
        
        $list = $userOpenHoursRepository->getOpenHoursByDayAndUser($data->userId, $data->date, $data->categoryId);

        $tab = [];
        foreach($list as $row){

            $mine = false;
            if(null != $this->getUser()){
                if($row->getUserHasBooked()->getId() == $this->getUser()->getId()){
                    $mine = true;
                }
            }


            $tab[] = [
                'id' => $row->getOpenHours()->getId(),
                'start_hours' => $row->getOpenHours()->getStartHours()->format('H:i'),
                'end_hours' => $row->getOpenHours()->getEndHours()->format('H:i'),
                'isBooked' => $row->isIsBooked(),
                'mine' => $mine
            ];
        }

        return new JsonResponse($tab);
    }

    #[Route('/reservation/{openHours}/{presta}', name: 'app_presta_reservation')]
    public function reservation(OpenHours $openHours, User $presta, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();

        if(!$this->getuser()){

            $session->set('TEMP_SESION_HOURS', $openHours->getId());
            $session->set('TEMP_SESSION_PRESTA', $presta->getId());

            $this->addFlash('danger', 'Vous devez etre connecter pour pouvoir réserver un creneau');
            return $this->redirectToRoute('app_login');

        }else{

            if(!empty($session->get('TEMP_SESION_HOURS'))){
                $session->remove('TEMP_SESION_HOURS');
            }

        }

        foreach($openHours->getUserOpenHours() as $row){
            $userOpenHours = $row;
        }

        return $this->render('presta/reservation.html.twig', [
            'presta' => $presta,
            'openHours' => $openHours,
            'userOpenHours' => $userOpenHours
        ]);
    }

    #[Route('/confirmreservation/{openHours}/{presta}', name: 'app_presta_confirmreservation')]
    public function confirmreservation(OpenHours $openHours, User $presta, UserOpenHoursRepository $userOpenHoursRepository): Response
    {
        //test pour savoir si le creneau n'est pas déjà reservé
        foreach($openHours->getUserOpenHours() as $row){
            if($row->isIsBooked() && $row->getUserHasBooked()->getId() != $this->getUser()->getId()){
                $this->addFlash('danger', 'Ce creneau vient d\'etre réserver, merci d\'en choisir un autre.');
                return $this->redirectToRoute('app_presta', ['user' => $presta->getId()]);
            }
            $userOpenHours = $row;
        }

        //enregistrement de la reservation
        $userOpenHours->setIsBooked(true);
        $userOpenHours->setUserHasBooked($this->getUser());
        
        $userOpenHoursRepository->save($userOpenHours, true);

        return $this->render('presta/confirmreservation.html.twig', [
            'presta' => $presta,
            'openHours' => $openHours,
            'userOpenHours' => $userOpenHours
        ]);
    }

    #[Route('/pdfreservation/{openHours}/{presta}', name: 'app_presta_pdfreservation')]
    public function pdfreservation(OpenHours $openHours, User $presta): void
    {
        foreach($openHours->getUserOpenHours() as $row){
            $userOpenHours = $row;
        }

        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Text(10,10, utf8_decode('Votre réservation du : ' . $openHours->getStartHours()->format('d/m/Y')));
        $pdf->Text(10,20, utf8_decode('De : ' . $openHours->getStartHours()->format('H:i') . ' a ' . $openHours->getEndHours()->format('H:i')));
        $pdf->Text(10,30, utf8_decode('Avec votre  : ' . $presta->getFirstname() . ' ' . $presta->getLastname()));
        $pdf->Text(10,40, utf8_decode('Pour une prestation de : ' . $userOpenHours->getCategory()->getName()));
        $pdf->Output();

        dd('ok');

    }
}
