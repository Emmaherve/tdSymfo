<?php

namespace App\Repository;

use App\Entity\Telephone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Telephone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Telephone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Telephone[]    findAll()
 * @method Telephone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelephoneRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Telephone::class);
    }


    // dans le repository
public function findBiggerSizeThan($value)
{
        // doit renvoyer un tableau d'entités correspondantes à la contrainte
        // comme la fonction findBy par exemple
        // récupération de l'em
        $em = $this->getEntityManager();

        // création de la requête
        $query = $em->createQuery(
            'SELECT t
            FROM App\Entity\Telephone t
            WHERE t.taille > :size'
        )->setParameter('size', $value);

        // exécution et renvoie de la requête sous la forme de tableau d'entités
        return $query->execute();

    }


public function findSearch($search)
  {

    $em = $this->getEntityManager();

    $query = $em->createQuery(
               'SELECT t
               FROM App\Entity\Telephone t
               WHERE t.marque LIKE :search
               ORDER BY t.marque ASC'
             )->setParameter('search','%'.$search.'%');

     // exécution et renvoie de la requête sous la forme de tableau d'entités
     return $query->execute();

}


public function findSearchTel($search,$searchType)
  {

    $em = $this->getEntityManager();

    $query = $em->createQuery(
               'SELECT t
               FROM App\Entity\Telephone t
               WHERE t.marque LIKE :search
               ORDER BY t.marque ASC'
             )->setParameter('search','%'.$search.'%');
    $query = $em->createQuery(
               'SELECT t
               FROM App\Entity\Telephone t
               WHERE t.type LIKE :searchType
               ORDER BY t.type ASC'
             )->setParameter('searchType','%'.$searchType.'%');

     // exécution et renvoie de la requête sous la forme de tableau d'entités
     return $query->execute();

}






    // /**
    //  * @return Telephone[] Returns an array of Telephone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Telephone
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
