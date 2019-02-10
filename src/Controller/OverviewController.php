<?php

namespace App\Controller;

use App\Entity\Slot;
use App\Repository\SlotRepository;
use App\Service\SlotsFiller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends AbstractController
{
    /**
     * @Route("/", name="overview")
     */
    public function index(SlotRepository $repository, SlotsFiller $filter)
    {
        $availableSlots = $filter->getToday(new \DateTime(), new \DateTime(date('Y-m-d 23:00:00')), 15);
        $reservations = $repository->findAll();
        $slots = $filter->merge($availableSlots, $reservations);

        /** @var Slot[] $reservations */
        /** @var Slot[] $slots */
        return $this->render('overview/index.html.twig', [
            "reservations" => $reservations,
            "slots" => $slots
        ]);
    }
}
