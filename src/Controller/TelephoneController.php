<?php

// le namespace des controllers sera toujours le même
namespace App\Controller;

// La classe Response nous sert pour renvoyer la réponse (voir après)
use Symfony\Component\HttpFoundation\Response;
// la classe Controller est la classe mère de tous les controllers
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//Pour pouvoir utiliser l’entité Telephone
use App\Entity\Telephone;
use App\Form\TelephoneType;

// //Pour les formulaires
// use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\CompoTelephoneForm;

// notre controller doit forcément hériter de la classe Controller ("use" ci-dessus)
// Le nom de la classe doit être exactement le même que celui du fichier
class TelephoneController extends Controller
{

  public function index_tel($marque,$type,$taille) //add function
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

  // public function modify_tel($marque,$type,$taille,$id)
  // {
  //   $em = $this->getDoctrine()->getManager();
  //   $tel = $this->getDoctrine()->getRepository(Telephone::class)->find($id);
  //   $tel->setMarque($marque);
  //   $tel->setType($type);
  //   $tel->setTaille($taille);
  //   $tel->getId($id);
  //   $em->flush();
  //   return $this->render('tel.html.twig', array(
  //     "marque" => $marque,
  //     "type" => $type,
  //     "taille" => $taille,
  //     )
  //   );
  // }

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


public function globaleSearch_tel($search, $searchType)
{
  $repo = $this->getDoctrine()->getRepository(Telephone::class);
  $searchTel = $repo -> findSearchTel($search, $searchType);

  return $this->render('search.html.twig', array(
    "searchTel" => $searchTel,

    )
  );
}



public function new_tel(Request $request)
{
    // Nous créons une entité Telephone
    $tel = new Telephone();

    // Nous créons un formulaire A PARTIR DE $tel
    // ce qui permettra à Symfony d'hydrater (remplir) cette entité une fois que le formulaire sera validé...



    // $form = $this->createFormBuilder($tel)
    //     // ... c'est pour cette raison qu'on des noms de champs qui correspondent à l'entité :
    //     // Symfony les reconnait et fait le lien directement
    //     // Nous précisons aussi le type de champs (TextType::class, NumberType::class)
    //     ->add('marque', TextType::class)
    //     ->add('type', TextType::class)
    //     ->add('taille', NumberType::class)
    //     // Et le petit bouton sauvegarde, sur lequel nous écrivons "Création"
    //     ->add('save', SubmitType::class, array('label' => 'Création'))
    //     // getForm permet de créer l'objet formulaire en lui-même
    //     ->getForm();

    // GRACE A LA CREATION DU FICHIER TelephoneType NOUS REMPLACONS LES LIGNES PRECEDENTES PAR CELLE-CI:

      // Nous précisons ici que nous voulons utiliser `TelephoneType` et hydrater $tel
      $form = $this->createForm(TelephoneType::class, $tel);


      // nous récupérons ici les informations du formulaire validée
      // c'est-à-dire l'équivalent du $_POST
      // ... et ce, grâce à l'objet $request
      // qui représente les informations sur la requête HTTP reçue (voir l'explication après le code)
      $form->handleRequest($request);


      // Si nous venons de valider le formulaire et s'il est valide (problèmes de type, etc)
      if ($form->isSubmitted() && $form->isValid()) {
        // nous enregistrons directement l'objet $tel !
        // En effet, il a été hydraté grâce au paramètre donné à la méthode createFormBuilder !

        $em = $this->getDoctrine()->getManager();
        $em->persist($tel);
        $em->flush();
        // nous redirigeons l'utilisateur vers la route /telephone/
        // nous utilisons ici l'identifiant de la route, créé dans le fichier yaml
        // (il est peut-être différent pour vous, adaptez en conséquence)
        // extrèmement pratique : si nous devons changer l'url en elle-même,
        // nous n'avons pas à changer nos contrôleurs, mais juste les fichiers de configurations yaml
        return $this->redirectToRoute('telephone_all');
      }


    // renvoie classique à Twig...
    return $this->render('new.html.twig', array(
    // en renvoyant l'objet qui va bien à partir de la méthode createView
    'form' => $form->createView(),
    )
  );
}



public function modify_tel(Request $request, $id)
{

// On récupère le téléphone avec l'id correspondant(celui qui a été entré dans l'url)
    $em = $this->getDoctrine()->getManager();
    $tel = $this->getDoctrine()->getRepository(Telephone::class)->findOneById($id);
    $tel->getId($id);
    $em->flush();


    $form = $this->createForm(TelephoneType::class, $tel);
    $form->handleRequest($request);

//On affiche le formulaire avec les infos du téléphones rentrés automatiquement dans les champs
    if ($form->isSubmitted() && $form->isValid()) {

        $tel->getId($id);
        $em->persist($tel);

        $em->flush();

//On affiche tous les téléphone : on remarque que le téléphone à l'id correspondant a été modifié
      return $this->redirectToRoute('telephone_all');

    }

    // renvoie classique à Twig...
    return $this->render('new.html.twig', array(
    // en renvoyant l'objet qui va bien à partir de la méthode createView
    'form' => $form->createView(),
    )
  );
}



}
?>
