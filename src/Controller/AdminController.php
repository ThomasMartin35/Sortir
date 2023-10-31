<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard/campus', name: 'admin_dashboard_campus')]
    public function manageCampus(): Response
    {
        return $this->render('admin/dashboardCampus.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/dashboard/city', name: 'admin_dashboard_city')]
    public function ManageCity(): Response
    {
        return $this->render('admin/dashboardCity.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
