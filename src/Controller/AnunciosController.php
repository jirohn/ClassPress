<?php

namespace App\Controller;

use App\Entity\Anuncios;
use App\Entity\Comentarios;
use App\Entity\Usuario;
use App\Form\AnunciosType;
use App\Form\ComentariosType;
use App\Form\ComentsType;
use App\Form\PostType;
use App\Form\UserType;
use Knp\Component\Pager\PaginatorInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
class AnunciosController extends AbstractController
{
    /**
     * @Route("/anuncios", name="anuncios")
     */
    public function index(Request $request, SluggerInterface $slugger): Response
    {

        $anuncios = new Anuncios();
        $form = $this->createForm(PostType::class, $anuncios);
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
                $anuncios->setFotos($newFilename);
            }
            $user = $this->getUser();
            $anuncios->setUsuario($user);
            $en = $this->getDoctrine()->getManager();
            $en->persist($anuncios);
            $en->flush();
            $this->addFlash("Exito", Anuncios::ANUNCIO_EXITOSO);
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('anuncios/index.html.twig', [
            'controller_name' => 'anuncios',
            'formu' => $form->createView()
        ]);
    }

    /**
     * @Route("/anuncios/{id}", name="veranuncio")
     */
    public function vePost($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Anuncios::class)->find($id);
        $comentarios = $em->getRepository(Comentarios::class)->findBy(['anuncio'=>$post]);
        $comentario = new Comentarios();
        $form = $this->createForm(ComentsType::class, $comentario);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $comentario->setUsuario($user);
            $anun = $post->getId();

            $comentario->setAnuncio($post);
            $en = $this->getDoctrine()->getManager();
            $en->persist($comentario);
            $en->flush();
            return $this->redirect($request->getUri());
        }
            return $this->render('anuncios/veranuncios.html.twig', [
                'post' => $post,
                'form' => $form->createView(),
                'comentarios' => $comentarios
            ]);

    }
    /**
     * @Route("/misanuncios", name="misanuncios")
     */
    public function MisPosts(){
        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $posts = $em->getRepository(Anuncios::class)->findBy(['usuario'=>$user]);
        return $this->render('anuncios/misanuncios.html.twig', [
            'posts' => $posts

        ]);
    }
    /**
     * @Route("/todosanuncios", name="todosanuncios")
     */
    public function TodosPosts(PaginatorInterface $paginator, Request $request)
        {
            $em=$this->getDoctrine()->getManager();
            $query = $em->getRepository(Anuncios::class)->BuscarPosts();
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                3 /*limit per page*/
            );
            return $this->render('anuncios/misanuncios.html.twig', [
                'pagination' => $pagination,

            ]);
        }
    /**
     * @Route("/Likes" , options={"expose"=true}, name="Likes")
     */
    public function Like(Request $request){
        if($request->isXmlHttpRequest()){
            $em=$this->getDoctrine()->getManager();
            $user = $this->getUser();
            $id=$request->request-> get('id');
            $post = $em->getRepository(Anuncios::class)->find($id);
            $likes = $post->getLikes();
            $userLikes = explode(",", $likes);

            $usermakeLike = false;
                        foreach($userLikes as $us){
                if($us == $user->getId()){
                    $usermakeLike=true;
                }

            }
            if($usermakeLike==false){
                print($likes);
                $likes .= $user->getId().',';

            }else{
                if (($key = array_search($user->getId(), $userLikes)) !== false) {
                    unset($userLikes[$key]);
                    $str = implode(',', $userLikes);
                    $likes=$str;
                }


            }
            $post->setLikes($likes);
            $em->flush();

            return new JsonResponse(['likes'=>$likes]);

        }else{
            throw new \Exception('me quieres hackear?');
        }
    }
}
