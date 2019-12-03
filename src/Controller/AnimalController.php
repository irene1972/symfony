<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Animal;

class AnimalController extends AbstractController
{
    public function index()
    {
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
        ]);
    }

    public function save()
    {
        //return new Response('Hola mundillo');

        //**************************************************** */
        //Guardar en una tabla de la bd
        //**************************************************** */
        //1. Cargo el entity manager
        $entityManager = $this->getDoctrine()->getManager();

        //2. Creo el objeto y le doy valores
        $animal = new Animal();
        $animal->setTipo('Avestruz');
        $animal->setColor('verde');
        $animal->setRaza('africana');

        //3. Persistir el objeto mediante doctrine (guarda en memoria de doctrine el/los objetos)
        $entityManager->persist($animal);

        //4. Volcar datos en la tabla (es decir guardar en tabla)
        $entityManager->flush();

        return new Response('El animal guardado tiene el id: ' . $animal->getId());
    }
}
