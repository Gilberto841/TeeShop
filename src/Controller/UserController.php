<?php

namespace App\Controller;

use DateTime;

use App\Entity\User;
use App\Form\ResgisterFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
  #[Route('/inscription', name: 'register', methods: ['GET', 'POST'])]
  public function register(Request $request, UserRepository $repository, UserPasswordHasher $passwordHasher): Response 
  {
     $user = new User();
     $form = $this->createForm(ResgisterFormType::class, $user)
         ->handleRequest($request);

      if($form->isSubmitted() && $form->isValid(){

        # Set les propriétés qui ne sont pas dans le formulaire Et obligatoires en BDD,
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(new DateTime());
        # Set les rôles du User. cette propriété est un array[]
        $user->setRoles(['ROLE_USER']);

        dump($user->getPassword());

        # on doit resseter manuellement la valeur du password , car par défaut il n'est pas hashé<div class="# Pour ce la , nous devons utiliser une méthode de hashage appelée hashPassword()"
        # => cette méthode attend 2 arguments : $user, $plaintPassword  

        $user->setPassword(
            $passwordHasher->hashPassword($user, $user->getpassword())
        )

        $user->setPassword

        $repository->save($user, flush:true);

        $this->addFlash( type:'success', message: "Votre inscription a été correctement enregistrée !");    
        return $this->redirectToRoute(route:'show_home');
      })   

     return $this->render('user/register_form.html.twig', [
         'form' => $form->createView()
     ]); 
  } // end register()
} // end claee{}