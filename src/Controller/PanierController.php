<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier')]
class PanierController extends AbstractController
{
    # Créer l'action qui va rendre la vue 'panier/show_panier.html.twig'
    #[Route('/voir-mon-panier', name: 'show_panier', methods: ['GET'])]
    public function showPanier(SessionInterface $session): Response
    {
        # Cette instruction permet de récupérer le panier en session, et à défaut créer le panier s'il n'existe pas.
        $panier = $session->get('panier', []);
        $total = 0;

        # EXERCICE : Faire le code qui permet de calculer le total par produit ET le total du panier.
        foreach($panier as $item) {

            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

         return $this->render('panier/show_panier.html.twig', [
            'total' => $total
         ]);
        
    }// end function showPanier()

    #[Route('/ajouter-un-produit/{id}', name: 'add_item', methods: ['GET'])]
    public function addItem(Product $product, SessionInterface $session): Response
    {
        # Si dans la session le panier n'existe pas, la méthode get() retournera le second paramètre : un array vide
        $panier = $session->get('panier', []);

        if(empty($panier[$product->getId()])) {
            $panier[$product->getId()]['quantity'] = 1;
            $panier[$product->getId()]['product'] = $product;
        } else {
            // post-incrementation : quantity + 1
            // pre-incrementation : 1 + quantity
            ++$panier[$product->getId()]['quantity'];
        }

        # Ici, nous devons set() le panier en session, en lui passant $panier[]
        $session->set('panier', $panier);

        $this->addFlash('success', "Le produit a bien été ajouté à votre panier !");
        return $this->redirectToRoute('show_home');
    } // end function addItem()

    #[Route('/vider-le-panier', 'delete_Panier',['GET'])]
    public function deletePanier(SessionInterface $session): Response
    {
        $session->remove('panier');

        $this->addFlash('success', "Votre panier est à nouveau vide.");
        return $this->redirectToRoute('show_home');

    }

    #Exercice : créer l'action qui permet de supprimer un seul item du panier :
    #[Route('/retirer-du-panier/{id}', 'delete-item', ['GET'])]
    public function deleteItem(int $id, SessionInterface $session): Response
    {
        $panier = $session->get('panier');

        if array_key_exists($panier) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('show_panier'); 
    }





} // end class