<?php

namespace App\Controller;

use App\Entity\Anuncios;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
        $query = $em->getRepository(Anuncios::class)->BuscarPosts();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
        return $this->render('dashboard/index.html.twig', [
            'pagination' => $pagination,

        ]);
    }
}
