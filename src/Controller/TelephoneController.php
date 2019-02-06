<?php

// le namespace des controllers sera toujours le même
namespace App\Controller;

// La classe Response nous sert pour renvoyer la réponse (voir après)
use Symfony\Component\HttpFoundation\Response;
// la classe Controller est la classe mère de tous les controllers
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//Pour pouvoir utiliser l’entité Telephone
use App\Entity\Telephone;

// notre controller doit forcément hériter de la classe Controller ("use" ci-dessus)
// Le nom de la classe doit être exactement le même que celui du fichier
class TelephoneController extends Controller
{

  private function index_tel($marque,$type,$taille) //add function
  {
   $tel = new Telephone();

    $tel->setMarque($marque);
    $tel->setType($type);
    $tel->setTaille($taille);

    $em = $this->getDoctrine()->getManager();
    $em->persist($tel);
    $em->flush();
   }


  public function index_tel_perso($marque, $type, $taille) //add function
  {
    $this -> index_tel($marque,$type,$taille);

     return $this->render('tel.html.twig', array(
       	"marque" => $marque,
       	"type" => $type,
       	"taille" => $taille,
    ));
  }

  public function modify_tel($marque,$type,$taille,$id){
  $em = $this->getDoctrine()->getManager();
  $tel = $this->getDoctrine()->getRepository(Telephone::class)->find($id);
  $tel->setMarque($marque);
  $tel->setType($type);
  $tel->setTaille($taille);
  $tel->getId($id);
  $em->flush();


  return $this->render('tel.html.twig', array(
      "marque" => $marque,
      "type" => $type,
      "taille" => $taille,
    )
  );
}

public function delete_tel($id) //Supprime le téléphone avec l'id entré
{
 $em = $this->getDoctrine()->getManager();
 $tel = $this->getDoctrine()->getRepository(Telephone::class)->find($id);
 $tel->getId($id);
 $em->remove($tel);
 $em->flush();

 return new Response('un suppositoire qui rouleeee!');

}


public function all_tel()
{

  $em = $this->getDoctrine()->getManager();
    $repo = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository(Telephone::class)
    ;

    $tel = $repo->findAll();
    $em->flush();
    // ...

    return $this->render('all_telephone.html.twig', array(
      "tel" => $tel,
      )
    );

}



public function index()
{
  $repo = $this->getDoctrine()->getRepository(Telephone::class);
  $tailleTel = $repo -> findBiggerSizeThan(5.5);

  return $this->render('telephoneTaille.html.twig', array(
    "tailleTel" => $tailleTel,
    )
  );

}



public function search_tel($search)
{
  $repo = $this->getDoctrine()->getRepository(Telephone::class);
  $searchTel = $repo -> findSearch($search);

  return $this->render('search.html.twig', array(
    "searchTel" => $searchTel,
    )
  );
}


}
?>
