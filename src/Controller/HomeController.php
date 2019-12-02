<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'hello' => 'Hola mundo con Symfony 4',
        ]);
    }

    public function animales( $nombre, $apellidos )
    {
        $title='Bienvenido a la página de Animales';
        $animales=array('perro', 'gato', 'paloma', 'rata');
        $aves=array(
            'tipo' => 'palomo', 
            'color' => 'gris', 
            'edad' => 4, 
            'raza' => 'colillano'
        );

        return $this->render('home/animales.html.twig',[
            'title' => $title,
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'animales' => $animales,
            'aves' => $aves,
        ]);
    }

    public function redirigir(){
        //metodo1
        //*******/
        //return $this->redirectToRoute('index');

        //return $this->redirectToRoute('index', array(), 301);
        
        // return $this->redirectToRoute('animales', [
        //     'nombre' => 'Juan Pedro',
        //     'apellidos' => 'Lopez',
        // ]);

        //metodo2
        //*******/
        return $this->redirect('https://translate.google.es/');

    }
}
