<?php

namespace App\Controller;

use App\Entity\Personne;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class PersonneController extends AbstractController
{
    /**
     * @Route("/personne", name="list_personne", methods={"GET"})
     */
    public function list(ManagerRegistry $doctrine): JsonResponse
    {

        $personne = $doctrine->getRepository(Personne::class)->findBy(array(), array('nom' => 'ASC' ));
        $data = [];
        foreach($personne as $p){
            $data[] = [
                'nom' => $p->getNom(),
                'prenom' => $p->getPrenom(),
            ];
        }

        return new JsonResponse($data);
    }

        /**
     * @Route("/personne", name="add_personne", methods={"POST"})
     */
    public function add(Request $request, ManagerRegistry $doctrine)
    {

        $personne = new Personne();
        $personne->setNom($request->query->get('nom'));
        $personne->setPrenom($request->query->get('prenom'));
        $personne->setBirthday(new DateTime($request->query->get('birthdate')));


        $em = $doctrine->getManager();
        $em->persist($personne);

        $em->flush();

        return new Response("L'utilisateur ". $personne->getNom() . " avec l'id ".$personne->getId()." a bien était sauvegardé");



    }
}