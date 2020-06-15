<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }


    public function search(array $filters = [])
    {
        //constructeur qui nous permet de construire la requete SQL
        //avec alias "a" pour l'entité (la table) Article
        $builder = $this->createQueryBuilder('a');

        //tri par date de publication décroissante
        $builder->orderBy('a.publicationDate', 'DESC');

        //une condition pour chacun des filtres

        if(!empty($filters['title'])){

            $builder
                //ajoute un élément à la clause WHERE
                ->andWhere('a.title LIKE :title')
                //bindValue du marqueur: title
                ->setParameter('title', '%'.$filters['title'].'%')
            ;
        }

        if(!empty($filters['category'])){

            $builder
                //on utilise l'attribut $category de l'entité Article
                    //et non le champ category_id de la table en bdd
                ->andWhere('a.category = :category')
                ->setParameter('category', $filters['category'])
            ;
        }

        if(!empty($filters['start_date'])){

            $builder
                ->andWhere('a.publicationDate >= :start_date')
                ->setParameter('start_date', $filters['start_date'])
            ;
        }

        if(!empty($filters['end_date'])){

            $builder
                ->andWhere('a.publicationDate <= :end_date')
                ->setParameter('end_date', $filters['end_date'])
            ;
        }

        //objet Query généré
        $query = $builder->getQuery();

        //pour voir en chaine de caractère le SQL qui correspond pour debug
        //echo $query->getSQL();

        //retourne un tableau d'objet Article correspondant à la requête
        return $query->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
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
    public function findOneBySomeField($value): ?Article
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
