<?php

namespace App\Repository;

use App\Entity\Anuncios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Anuncios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Anuncios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Anuncios[]    findAll()
 * @method Anuncios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnunciosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Anuncios::class);
    }
    public function BuscarPosts(){
        return $this->getEntityManager()
        ->createQuery('
        SELECT post.id, post.titulo, post.fotos, post.fecha, post.contenido, user.nombre
        From App:Anuncios post
        JOIN post.usuario user
        ');

    }
    public function BuscarTodosPosts()
    {
        return $this->getEntityManager()
            ->createQuery('
        SELECT post.id, post.titulo, post.fotos, post.fecha
        From App:Anuncios post
        ');
    }
    // /**
    //  * @return Anuncios[] Returns an array of Anuncios objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Anuncios
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
