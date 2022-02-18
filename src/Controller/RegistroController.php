<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistroController extends AbstractController
{
    /**
     * @var UserPasswordHasherInterface
     */

    /**
     * @Route("/registro", name="registro")
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UserType::class, $usuario);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $en = $this->getDoctrine()->getManager();
            $this->passwordHasher = $passwordHasher;
            $usuario->setPassword($this->passwordHasher->hashPassword($usuario, $form['password']->getData()));
            $en->Persist($usuario);
            $en->flush();
            $this->addFlash("Exito",Usuario::REGISTRO_EXITOSO);
            return $this->redirectToRoute('registro');
        }
        return $this->render('registro/index.html.twig', [
            'controller_name' => 'RegistroController',
            'formu'=>$form->createView()
        ]);
    }
}
