<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;

class AnimalController extends AbstractController
{
    public function index()
    {
        $doctrine = $this->getDoctrine();
        $animal_repo = $doctrine->getRepository(Animal::class);
        //$em = $doctrine->getManager();

        $animales = $animal_repo->findAll();

        //var_dump($animales);

        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animales' => $animales
        ]);
    }

    public function irene_find_by_query_builder()
    {
        $doctrine = $this->getDoctrine();
        $animal_repo = $doctrine->getRepository(Animal::class);

        // $qb = $animal_repo->createQueryBuilder('a')
        //                     ->andWhere("a.raza = 'americana'")
        //                     ->getQuery();
        
        $qb = $animal_repo->createQueryBuilder('a')
                            ->andWhere("a.raza = :raza")
                            ->setParameter('raza', 'africana')
                            ->orderBy('a.id', 'DESC')
                            ->getQuery();
        
        $resultset = $qb->execute();

        //var_dump($resultset);
        
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animales' => $resultset
        ]);
    }

    public function irene_find_by_query_builder_using_repo()
    {
        $doctrine = $this->getDoctrine();
        $animal_repo = $doctrine->getRepository(Animal::class);
        
        //$qb = $animal_repo->createQueryBuilder('a')
                            //->andWhere("a.raza = :raza")
                            //->setParameter('raza', 'africana')
                            //->orderBy('a.id', 'DESC')
                            //->getQuery();
        
        //$resultset = $qb->execute();

        //var_dump($resultset);
        
        //REPOSITORIO
        $animales = $animal_repo->findByRaza( 'DESC' );
        //var_dump($animales);
        
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animales' => $animales
        ]);
    }

    public function irene_find_by_DQL()
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        
        //$dql = "SELECT a FROM App\Entity\Animal a WHERE a.raza = 'americana'";
        $dql = "SELECT a FROM App\Entity\Animal a ORDER BY a.id DESC";
        $query = $em->createQuery( $dql );
        $resultset = $query->execute();

        //var_dump($resultset);
        
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animales' => $resultset
        ]);
    }

    public function irene_find_by_SQL()
    {
        $connection = $this->getDoctrine()->getConnection();
        
        $sql = "SELECT * FROM animales ORDER BY id DESC";
        $prepare = $connection->prepare( $sql );
        $prepare->execute();
        //$resultset = $prepare->fetch();
        $resultset = $prepare->fetchAll();

        //var_dump($resultset);
        
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animales' => $resultset
        ]);
    }

    public function irene_find_one_by_condition()
    {
        $animal_repo = $this->getDoctrine()->getRepository(Animal::class);

        $animal_concreto = $animal_repo->findOneBy([
            'raza' => 'africana',
        ]);

        var_dump( $animal_concreto );

        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animal_concreto' => $animal_concreto
        ]);
    }

    public function irene_find_by_condition()
    {
        $animal_repo = $this->getDoctrine()->getRepository(Animal::class);

        $animales_concretos = $animal_repo->findBy([
            //'tipo' => 'Vaca',
            'raza' => 'africana',
        ], [
            'id' => 'DESC'
        ]);

        var_dump( $animales_concretos );

        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animales_concretos' => $animales_concretos
        ]);
    }

    public function save( Request $request )
    {
        //var_dump( $request );
        //var_dump( $request->get('form') );
        //$arr_form = $request->get('form');
        //$tipo = $arr_form['tipo'];

        //die('www');
    }

    public function save_original()
    {
        //return new Response('Hola desde la acción save');

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

    public function animal( Animal $animal )
    {
        //return new Response('Hola desde la acción animal');

        //1. Cargar repositorio. Me permite traer todos los métodos de consulta que tiene nuestro modelo por defecto + los que hayamos añadido si le configuramos un repositorio
        //$animal_repo = $this->getDoctrine()->getRepository(Animal::class);

        //2. Consulta
        //$animal = $animal_repo->find( $id );

        //IMPORTANTE!! -> de esta manera nos saltamos los pasos 1 y 2. Al pasar el parámetro como objeto de tipo animal él solo hacel el find y devuelve el resultado o no pasándole igualmente el id por parámetro

        //3. Comprobar si el resultado es correcto
        if( !$animal ){
            $message = 'El animal no existe';
        }else{
            $message = 'Tu animal elegido es: ' . $animal->getTipo() .  ' - ' . $animal->getRaza();
        }

        return new Response( $message );
    }

    public function animal_original( $id )
    {
        //return new Response('Hola desde la acción animal');

        //1. Cargar repositorio. Me permite traer todos los métodos de consulta que tiene nuestro modelo por defecto + los que hayamos añadido si le configuramos un repositorio
        $animal_repo = $this->getDoctrine()->getRepository(Animal::class);

        //2. Consulta
        $animal = $animal_repo->find( $id );

        //3. Comprobar si el resultado es correcto
        if( !$animal ){
            $message = 'El animal no existe';
        }else{
            $message = 'Tu animal elegido es: ' . $animal->getTipo() .  ' - ' . $animal->getRaza();
        }

        return new Response( $message );
    }

    public function update( $id ){

        //Cargar doctrine
        $doctrine = $this->getDoctrine();

        //Cargar el entity manager
        $em = $doctrine->getManager();

        //Cargar el repo de la entidad Animal
        $animal_repo = $em->getRepository(Animal::class);

        //Hacer un Find para sacar el objeto
        $animal = $animal_repo->find( $id );

        //Comprobar el resultado del Find
        if( !$animal ){
            $message= 'El animal no existe en la bbdd';
        }else{
            //Asignarle los valores al objeto
            $animal->setTipo("Perro $id");
            $animal->setColor('rojo');

            //Persistir en doctrine el objeto
            $em->persist( $animal );
            
            //Guardar en la bd
            $em->flush();

            $message= 'Has actualizado el animal ' . $animal->getId();

        }

        //Respuesta
        return new Response($message);

    }

    public function delete( Animal $animal ){
        //IMPORTANTE!! Cuidado, si le pasamos un id que no existe o ya fue borrado explota porque no puede hacer uso de la variable $animal.
        //var_dump($animal);

        if( $animal && is_object( $animal ) ){

            $em = $this->getDoctrine()->getManager();
            $em->remove( $animal );
            $em->flush();

            $message = "Animal borrado correctamente";
        }else{
            $message = "Animal no encontrado";
        }

        

        return new Response( $message );

    }

    public function crearAnimal( Request $request ){

        $animal = new Animal();

        //en lugar de crear aquí el formulario, llamaos a la clase de este form y lo creamos con un método de ésta
        $form = $this->createForm( AnimalType::class, $animal );

        $form->handleRequest( $request );

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();
            $em->persist($animal);
            $em->flush();

            $session = new Session();
            $session->getFlashBag()->add('message', 'Animal creado');

            return $this->redirectToRoute( 'crear_animal' );

        }

        return $this->render('animal/crear-animal.html.twig', [
            'form' => $form->createView()
        ]);

    }

    public function crearAnimal_original( Request $request ){

        $animal = new Animal();

        //creamos un formulario para guardar un objeto animal
        $form = $this->createFormBuilder( $animal )
                        // ->setAction($this->generateUrl('animal_save'))  //cuando no quiero que los datos se reciban en este método sino que quiero enviarlos en otro le cambiamos el ACTION (la ruta 'animal_save' es una url extraída de routes.yaml)
                        // ->setMethod('POST')     //no sería necesario porque el método por defecto es POST
                            ->add('tipo',TextType::class, [
                                'label' => 'Tipo de animal',
                            ])
                            ->add('color',TextType::class)
                            ->add('raza',TextType::class)
                            ->add('submit',SubmitType::class, [
                                'label' => 'Crear animal',
                                'attr' => ['class' => 'btn btn-success'],
                            ])
                        ->getForm();

        //********************************************************************* */
        // Recuperamos la información submiteada y la llevamos hasta la plantilla
        //********************************************************************* */
        $form->handleRequest( $request );   //recoge los datos del formulario y los adjunta al objeto Animal

        if( $form->isSubmitted() && $form->isValid() ){

            //var_dump($animal);
            $em = $this->getDoctrine()->getManager();
            $em->persist($animal);
            $em->flush();

            //Session flash
            $session = new Session();
            $session->getFlashBag()->add('message', 'Animal creado');

            return $this->redirectToRoute( 'crear_animal' );

        }

        return $this->render('animal/crear-animal.html.twig', [
            'form' => $form->createView()
        ]); //le pasamos una vista donde vamos a renderizar los datos

    }

    public function validarEmail( $email ){

        var_dump($email);
        
        $validator = Validation::createValidator();
        $errores = $validator->validate( $email,[
            new Email()
        ] );

        if( count($errores) != 0 ){
            
            echo "El email NO HA SUPERADO la validación<br/>";

            foreach( $errores as $error ){
                echo $error->getMessage() . '<br/>';
            }

        }else{
            echo "El email HA SIDO VALIDADO correctamente";
        }

        die('sss');

    }

}
