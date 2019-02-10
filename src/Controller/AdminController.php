<?php

namespace App\Controller;

use App\Entity\Slot;
use App\Form\SlotType;
use App\Repository\SlotRepository;
use App\Service\SlotsFiller;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(SlotRepository $repository, SlotsFiller $filter)
    {
        $availableSlots = $filter->getToday(new \DateTime(), new \DateTime(date('Y-m-d 23:00:00')), 15);
        $reservations = $repository->findAll();
        $slots = $filter->merge($availableSlots, $reservations);

        /** @var Slot[] $reservations */
        /** @var Slot[] $slots */
        return $this->render('admin/index.html.twig', [
            "reservations" => $reservations,
            "slots" => $slots
        ]);
    }

    /**
     * @Route("/admin/reserve/{time}", name="reserve")
     */
    public function reserve(\DateTime $time, Request $request, EntityManagerInterface $manager)
    {
        $slot = new Slot();
        $slot->setDate($time);
        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $manager->persist($form->getData());
                $manager->flush();
                $this->addFlash(
                    'info',
                    'Rezervacija atlikta'
                );
                return $this->redirectToRoute("admin");
            } catch (ORMException $exception) {
                return $this->render('admin/error.html.twig', [
                    "error" => $exception->getMessage(),
                ]);
            }
        }

        /** @var Slot[] $reservations */
        return $this->render('admin/reserve.html.twig', [
            "inputForm" => $form->createView(),
        ]);
    }
}
