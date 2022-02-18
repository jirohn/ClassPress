<?php

namespace App\Controller;

use App\Entity\Anuncios;
use App\Entity\Config;
use App\Entity\DashModules;
use App\Entity\Modules;
use App\Entity\Usuario;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModulesController extends AbstractController
{
    /**
     * @Route("/modules", name="modules")
     */
    public function index(): Response
    {
        return $this->render('modules/index.html.twig', [
            'controller_name' => 'ModulesController',
        ]);
    }

    //Modulos predefinidos basicos

    /**
     * @Route("/modules/usuarios", name="modulousuarios")
     */
    public function usuariosmodule(PaginatorInterface $paginator, Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
        $users = $em->getRepository(Usuario::class)->findAll();
        $config = $em->getRepository(Config::class)->find(1);
        $query = $em->getRepository(Usuario::class)->BuscarUsuarios();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('modules/usuarios.mod.html.twig', [
            'users' => $users,
            'pagination' => $pagination,
            'config' => $config
        ]);
    }
    /**
     * @Route("/modules/addmod" , options={"expose"=true}, name="AddMod")
     */
    public function AddMod(Request $request){
        if($request->isXmlHttpRequest()){
            $module = new DashModules();
            $em=$this->getDoctrine()->getManager();
            $id=$request->request-> get('id');
            $id = intval($id);
            //$bar=$request->request-> get('bar');
            $modtocall = $em->getRepository(Modules::class)->find($id);
            $module->setmodulo($modtocall);
            $module->setBar(0);
            $em->persist($module);
            $em->flush();
            return new JsonResponse(['passed'=>true]);

        }else{
            throw new \Exception('me quieres hackear?');
        }
    }
}
