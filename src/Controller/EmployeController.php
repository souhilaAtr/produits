<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EmployeController extends AbstractController
{
    /**
     * @Route("/addemploye", name="app_employe")
     */
    public function index(ObjectManager $om, Request $request, SluggerInterface $slugger): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //ajouter un fichier 
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFilename);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('dossierImage'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                $employe->setImage($newFileName);
            }
            $om->persist($employe);
            $om->flush();
            return $this->redirectToRoute('showall');
        }

        return $this->render('employe/index.html.twig', [
            "formulaire" => $form->createView()

        ]);
    }
}
