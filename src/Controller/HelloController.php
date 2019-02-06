<?php

// le namespace des controllers sera toujours le même
namespace App\Controller;

// La classe Response nous sert pour renvoyer la réponse (voir après)
use Symfony\Component\HttpFoundation\Response;
// la classe Controller est la classe mère de tous les controllers
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// notre controller doit forcément hériter de la classe Controller ("use" ci-dessus)
// Le nom de la classe doit être exactement le même que celui du fichier
class HelloController extends Controller
{
    // public function index()
    // {
    //     // on renvoie ici un texte simple. Une instance de Response doit toujours être renvoyée.
    //     // return new Response("Hello World !"); // modifié et remplacé par la commande ci-dessous
    //     // return $this->render('hello.html.twig');
    //     $prenom = "Emma";
    //     $prenom1 = "Nathalie";
    //     $prenom2 = "Jacques";
    //
    //     $tableau_prenom =  array("Emma", "Nathalie", "Jacques");
    //
    //      return $this->render('helloprenom.html.twig', array(
    //        "tableau_prenom" => $tableau_prenom,
    //      ));
    // }

    public function index_perso($prenom, $age)
    {
      return $this->render('hello.html.twig', array(
        "prenom" => $prenom,
        "age" => $age,
      ));
    }

    public function index_basic()
    {
      return $this->render('_helloperso.html.twig');
    }

    public function index_erreur($prenom)
    {
      return $this->render('erreur.html.twig', array(
        "prenom" => $prenom,
      ));
    }

}


?>
