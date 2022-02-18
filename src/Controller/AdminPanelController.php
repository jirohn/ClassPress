<?php

namespace App\Controller;

use App\Entity\Anuncios;
use App\Entity\Config;
use App\Entity\DashModules;
use App\Entity\Modules;
use App\Entity\Usuario;
use App\Form\ConfigType;
use App\Form\PostType;
use Knp\Component\Pager\PaginatorInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminPanelController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_panel")
     */
    public function index(Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
        $users = $em->getRepository(Usuario::class)->findAll();
        $config = $em->getRepository(Config::class)->find(1);
        $anuncios = $em->getRepository(Anuncios::class)->findAll();
        return $this->render('admin_panel/index.html.twig', [
            'users' => $users,
            'anuncios' => $anuncios,
            'config' => $config
        ]);
    }
    /**
     * @Route("/admin/loaders", name="admin_loaders")
     */
    public function Loader(Request $request): Response
    {
        return $this->render('admin_panel/loader.html.twig', [

        ]);
    }
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function Dashboard(Request $request): Response
    {
        $em=$this->getDoctrine()->getManager();
        $users = $em->getRepository(Usuario::class)->findAll();
        $config = $em->getRepository(Config::class)->find(1);
        $anuncios = $em->getRepository(Anuncios::class)->findAll();
        $instModules = $em->getRepository(DashModules::class)->findAll();
        $modules = $em->getRepository(Modules::class)->findAll();
        return $this->render('admin_panel/dashboard.html.twig', [
            'users' => $users,
            'anuncios' => $anuncios,
            'config' => $config,
            'instmodules' => $instModules,
            'modules' => $modules
        ]);
    }
    /**
     * @Route("/admin/anuncios", name="administraranuncios")
     */
    public function AdministrarPosts(PaginatorInterface $paginator, Request $request){
        $em=$this->getDoctrine()->getManager();
        $query = $em->getRepository(Anuncios::class)->BuscarPosts();
        $anuncios = $em->getRepository(Anuncios::class)->findAll();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
        return $this->render('admin_panel/anuncios.html.twig', [
            'pagination' => $pagination,
            'anuncios' => $anuncios
        ]);
    }
    /**
     * @Route("/daradmin" , options={"expose"=true}, name="DarAdmin")
     */
    public function DarAdmin(Request $request){
        if($request->isXmlHttpRequest()){
            $em=$this->getDoctrine()->getManager();
            $id=$request->request-> get('id');
            $user = $em->getRepository(Usuario::class)->find($id);
            $rol =['ROLE_ADMIN'];
            if($user->getRoles() == ['ROLE_USER']){
                $rol =['ROLE_ADMIN'];
            }else{
                $rol =['ROLE_USER'];
            }
            $user->setRoles($rol);
            $em->flush();

            return new JsonResponse(['rol'=>$rol]);

        }else{
            throw new \Exception('me quieres hackear?');
        }
    }
    /**
     * @Route("/admin/config", name="Config")
     */
    public function Configuracion(Request $request, SluggerInterface $slugger):Response{

        $config = new Config();
        $form = $this->createForm(ConfigType::class, $config);
        $en = $this->getDoctrine()->getManager();
        $savedConf = $en->getRepository(Config::class)->find(1);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('fotos')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('fotos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception('ups! ha ocurrido un error sorry');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                if($savedConf==null) {
                    $config->setFotos($newFilename);
                }
                else{
                    $savedConf->setFotos($newFilename);
                }
            }
            if($savedConf==null){

                $en->persist($config);
            }

            $en->flush();

            return $this->redirect($request->getUri());
        }
        return $this->render('admin_panel/configuracion.html.twig', [
            'formu' => $form->createView(),
            'conf' => $savedConf,
            'config' => $savedConf
        ]);
    }
    /**
     * @Route("/admin/usuarios", name="adminUsuarios")
     */
    public function usuarios(PaginatorInterface $paginator, Request $request): Response
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
        return $this->render('admin_panel/usuarios.html.twig', [
            'users' => $users,
            'pagination' => $pagination,
            'config' => $config
        ]);
    }
    /**
     * @Route("/admin/confignombre" , options={"expose"=true}, name="ConfigNombre")
     */
    public function ConfigNombre(Request $request){
        if($request->isXmlHttpRequest()){
            $em=$this->getDoctrine()->getManager();
            $string=$_GET['texto'];;
            echo $string;

            $config = $em->getRepository(Config::class)->find(1);
            $config->setNombre($string);
            $em->flush();

            return new JsonResponse(['$string'=>$string]);

        }else{
            throw new \Exception('me quieres hackear?');
        }
        return $this->render('admin_panel/usuarios.html.twig', [
            'users' => $users,
            'pagination' => $pagination,
            'config' => $config
        ]);
    }
    /**
     * @Route("/admin/configtags" , options={"expose"=true}, name="ConfigTags")
     */
    public function ConfigTags(Request $request){
        if($request->isXmlHttpRequest()){
            $em=$this->getDoctrine()->getManager();
            $string=$_GET['texto'];;
            echo $string;

            $config = $em->getRepository(Config::class)->find(1);
            $config->setTags($string);
            $em->flush();

            return new JsonResponse(['$string'=>$string]);

        }else{
            throw new \Exception('me quieres hackear?');
        }
    }
    /**
     * @Route("/admin/configimage" , options={"expose"=true}, name="ConfigImage")
     */
    public function ConfigImage(Request $request, SluggerInterface $slugger):Response{
        if($request->isXmlHttpRequest()){
            $em=$this->getDoctrine()->getManager();
            $img=$_GET['texto'];;
            $config = $em->getRepository(Config::class)->find(1);
                $brochureFile = $img;
                if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $brochureFile->move(
                            $this->getParameter('fotos_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw new Exception('ups! ha ocurrido un error sorry');
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $config->setFotos($newFilename);
                }


            $em->flush();

            return new JsonResponse(['foto'=>$img]);

        }else{
            throw new \Exception('me quieres hackear?');
        }
    }
    /**
     * @Route("/admin/cambiarfoto", name="cambiarFoto")
     */
    public function cambiarFoto(Request $request, SluggerInterface $slugger):Response{
        $config = new Config();
        $form = $this->createForm(ConfigType::class, $config);
        $en = $this->getDoctrine()->getManager();
        $savedConf = $en->getRepository(Config::class)->find(1);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('fotos')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('fotos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception('ups! ha ocurrido un error sorry');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                if($savedConf==null) {
                    $config->setFotos($newFilename);
                }
                else{
                    $savedConf->setFotos($newFilename);
                }
            }
            if($savedConf==null){

                $en->persist($config);
            }

            $en->flush();

            return $this->redirect($request->getUri());
        }
        return $this->render('admin_panel/subirfoto.html.twig', [
            'formu' => $form->createView(),
            'conf' => $savedConf,
            'config' => $savedConf
        ]);
    }

}
