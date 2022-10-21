<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/", name="app_produits")
     */
    public function index(): Response
    {
        return $this->render('produits/index.html.twig', []);
    }

    /**
     * @Route("/showall", name="showall")
     */
    public function showAllProduct()
    {
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repository->findAll();
        return $this->render('produits/showall.html.twig', [
            "products" => $produits
        ]);
    }
    /**
     * @Route("showone/{id}", name="showone")
     */
    public function showOneProduct($id)
    {
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $repository->find($id);
        return $this->render('produits/showone.html.twig', [
            "produit" => $produit
        ]);
    }

    /**
     * @Route("/createone",name="createone")
     * @Route("/editproduct/{id}", name="editproduct")
     */
    public function editProduit(ObjectManager $om, Request $request, Produit $produit = null)
    {

        if (!$produit) {
            $produit = new Produit();
        }

        $formulaire = $this->createForm(ProduitType::class, $produit);
        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $produit->setCreatedAt(new \DateTimeImmutable());
            $om->persist($produit);
            $om->flush();
            return $this->redirectToRoute('showone', ["id" => $produit->getId()]);
        }
        $mode = false;
        if ($produit->getId() !== null) {
            $mode = true;
        }

        return $this->render("produits/createone.html.twig", [
            "formulaire" => $formulaire->createView(),
            "mode" => $mode
        ]);
    }

    /**
     * @Route("/supprimerproduit/{id}", name="supprimerproduit")
     */
    public function suppressionProduit(Produit $produit)
    {
        $om = $this->getDoctrine()->getManager();
        $om->remove($produit);
        $om->flush();
        return $this->redirectToRoute('showall');
    }

    /**
     * @Route("/produitbycat/{id}", name="produitbycat")
     */
    public function produitbycat(ManagerRegistry $doctrine, $id, Category $category)
    {
        $objecgtManager = $doctrine->getRepository(Category::class);
        $produits = $objecgtManager->findProduitsByCategory($id);
        // $category = new Category();
        // dd($produits[0]["id"]);
        // $idcat = $produits[0]["id"];
        // $cat = $objecgtManager->find($idcat);
        // dd($cat->getNom());
        return $this->render('produits/produitebycat.html.twig', [
            "produits" => $produits,
            "category" => $category
            // "nomCat" =>$cat->getNom()
        ]);
    }
}
