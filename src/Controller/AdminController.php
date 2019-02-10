<?php

namespace App\Controller;

use App\Entity\Slot;
use App\Form\SlotType;
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
    public function index(Request $request, EntityManagerInterface $manager)
    {
        $slot = new Slot();
        $slot->setDate(new \DateTime());
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

            return $this->render('admin/index.html.twig', [
                "inputForm" => $form->createView(),
                "formData" => $form->getData(),
                "state" => "submitted"
            ]);
        }

        return $this->render('admin/index.html.twig', [
            "inputForm" => $form->createView(),
            "state" => "ready"
        ]);
    }
}
