<?php

namespace App\Controller;

use App\Entity\Slot;
use App\Form\SlotType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request)
    {
        $slot = new Slot();
        $slot->setDate(new \DateTime());
        $form = $this->createForm(SlotType::class, $slot);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
