<?php

namespace App\Repository;

use App\Entity\{Octave, Tarif};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;

/**
 * @method Octave|null find($id, $lockMode = null, $lockVersion = null)
 * @method Octave|null findOneBy(array $criteria, array $orderBy = null)
 * @method Octave[]    findAll()
 * @method Octave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OctaveRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Octave::class);
    }

//    /**
//     * @return Octave[] Returns an array of Octave objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Octave
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
    * @param $fournisseurId
    * return Octave[]
    */
    public function comparateurPrix($fournisseurId) : array
    {
        return $this->getEm()->createQuery(
                "SELECT
                    o.id as idOctave,
                    o.code as oCode,
                    o.refFournisseur as oRefFournisseur,
                    o.prixVente as oPrixVente,
                    o.prixAchat as oPrixAchat,
                    o.remise as oRemise,
                    o.libelle as oLibelle,
                    o.isSpecial as oIsSpecial,
                    o.createdAt as oCreatedAt,
                    o.updatedAt as oUpdatedAt,
                    o.commentaire as oComment,
                    o.fournisseurId as oFournisseurId,
                    t.id as idTarif,
                    t.refFournisseur as tRefFournisseur,
                    t.prixVente as tPrixVente,
                    t.prixAchat as tPrixAchat,
                    t.groupeRemise as tGroupeRemise,
                    t.remise as tRemise,
                    t.description as tDescription,
                    t.createdAt as tCreatedAt,
                    t.updatedAt as tUpdatedAt
                FROM App\Entity\Octave o, App\Entity\Tarif t
                WHERE o.fournisseurId = :fournisseurId AND
                    t.fournisseurId = :fournisseurId AND
                    o.refFournisseur LIKE t.refFournisseur AND
                    o.isSpecial = 0
                "
            )
            ->setParameter('fournisseurId', $fournisseurId)
            ->execute()
        ;
    }

    /**
    * @param $fournisseurId
    * return Octave[]
    */
    public function mismatchs2($fournisseurId) : array
    {
        return $this->getEm()->createQuery(
                "SELECT o
                FROM App\Entity\Octave o
                WHERE o.fournisseurId = :fournisseurId AND
                    o.etat = 0 AND
                    (o.id NOT IN (
                        SELECT oo.id
                        FROM App\Entity\Octave oo, App\Entity\Tarif t
                        WHERE  oo.refFournisseur LIKE t.refFournisseur AND
                            t.fournisseurId = :fournisseurId
                        )
                    )
                "
            )
            ->setParameter('fournisseurId', $fournisseurId)
            ->execute()
        ;
    }

    /**
    * @param $fournisseurId
    * return Octave[]
    */
    public function mismatchs($fournisseurId) : array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                id,
                fournisseur_id as fournisseurId,
                code,
                ref_fournisseur as refFournisseur,
                libelle,
                marque,
                prix_vente as prixVente,
                prix_achat as prixAchat,
                etat,
                commentaire
            FROM octave o
            WHERE o.fournisseur_id = :fournisseurId AND
                o.etat = 0 AND
                (o.id NOT IN (
                    SELECT oo.id
                    FROM octave oo, tarif t
                    WHERE  oo.ref_fournisseur LIKE t.ref_fournisseur AND
                        t.fournisseur_id = :fournisseurId
                    )
                )
            '
        ;

        $stmt = $conn->prepare($sql);
        $stmt->execute(['fournisseurId' => $fournisseurId]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    /**
    * @param $fournisseurId
    * return array
    */
    public function allArticlesByFr($fournisseurId) : array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT o.id FROM octave o WHERE o.fournisseur_id = :fournisseurId';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['fournisseurId' => $fournisseurId]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();       
    }

    /**
    * @param $fournisseurId
    * return array
    */
    public function allArticlesByFrTarif($fournisseurId) : array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT t.id FROM tarif t WHERE t.fournisseur_id = :fournisseurId';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['fournisseurId' => $fournisseurId]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();       
    }

    /**
    * @param $isSpecial, $idOctave
    * return void
    */
    public function updateStatus($isSpecial, $idOctave) : void
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'UPDATE App\Entity\Octave o
            SET o.isSpecial = :isSpecial
            WHERE o.id = :idOctave
            '
        )
        ->setParameter('isSpecial', $isSpecial)
        ->setParameter('idOctave', $idOctave);
        
        $query->execute();      
    }

     /**
    * @param $etat, $idOctave
    * return void
    */
    public function updateEtat($etat, $idOctave) : void
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'UPDATE App\Entity\Octave o
            SET o.etat = :etat
            WHERE o.id = :idOctave
            '
        )
        ->setParameter('etat', $etat)
        ->setParameter('idOctave', $idOctave);
        
        $query->execute();      
    }
    
    /**
    * @param $refFr, $idOctave
    * return void
    */
    public function updateRefFournisseur($refFr, $idOctave) : void
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'UPDATE App\Entity\Octave o
            SET o.refFournisseur = :refFr
            WHERE o.id = :idOctave
            '
        )
        ->setParameter('refFr', $refFr)
        ->setParameter('idOctave', $idOctave);
        
        $query->execute();      
    }
    
    /**
    * @param $prix, $idOctave
    * return void
    */
    public function updatePrixSpecialAchat($prix, $idOctave) : void
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'UPDATE App\Entity\Octave o
            SET o.prixTSQ = :prix
            WHERE o.id = :idOctave
            '
        )
        ->setParameter('prix', $prix)
        ->setParameter('idOctave', $idOctave);
        
        $query->execute();      
    }

    /**
    * @param $prix, $idOctave
    * return void
    */
    public function updatePrixSpecialVente($prix, $idOctave) : void
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'UPDATE App\Entity\Octave o
            SET o.prixVenteTSQ = :prix
            WHERE o.id = :idOctave
            '
        )
        ->setParameter('prix', $prix)
        ->setParameter('idOctave', $idOctave);
        
        $query->execute();      
    }

    /**
    * @param $remise, $idOctave
    * return void
    */
    public function updateRemiseSpeciale($remise, $idOctave) : void
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'UPDATE App\Entity\Octave o
            SET o.remiseTSQ = :remise
            WHERE o.id = :idOctave
            '
        )
        ->setParameter('remise', $remise)
        ->setParameter('idOctave', $idOctave);
        
        $query->execute();      
    }

    /**
    * @param $comment, $idOctave
    * return void
    */
    public function updateCommentaire($comment, $idOctave) : void
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'UPDATE App\Entity\Octave o
            SET o.commentaire = :comment
            WHERE o.id = :idOctave
            '
        )
        ->setParameter('comment', $comment)
        ->setParameter('idOctave', $idOctave);
        
        $query->execute();      
    }

    /*
    * @param $fournisseurId
    * return number
    *
    public function allMismatchs($fournisseurId) : int
    {
        $query = $this->getEm()->createQuery(
                "SELECT COUNT(o.id)
                FROM App\Entity\Octave o
                WHERE o.fournisseurId = :fournisseurId AND
                    (o.id NOT IN (
                        SELECT oo.id
                        FROM App\Entity\Octave oo, App\Entity\Tarif t
                        WHERE  oo.refFournisseur LIKE t.refFournisseur AND
                            t.fournisseurId = :fournisseurId
                        )
                    )
                "
        );
        $query->setParameter('fournisseurId', $fournisseurId);

        return $query->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }
    */

    /*
    * @param $fournisseurId
    * return number
    *
    public function allArticlesByFr($fournisseurId) : int
    {
        $query = $this->getEm()->createQuery(
                "SELECT COUNT(o.id)
                FROM App\Entity\Octave o
                WHERE o.fournisseurId = :fournisseurId
                "
        );
        $query->setParameter('fournisseurId', $fournisseurId);

        return $query->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }
    */

    /**
    * @param $fournisseurId
    * return Octave[]
    */
    public function npu($fournisseurId) : array
    {
        return $this->getEm()->createQuery(
                "SELECT o
                FROM App\Entity\Octave o
                WHERE o.fournisseurId = :fournisseurId AND
                    o.etat=1
                "
            )
            ->setParameter('fournisseurId', $fournisseurId)
            ->execute()
        ;
    }

    /**
    * return Octave[]
    */
    public function allNpu() : array
    {
        return $this->getEm()->createQuery(
                "SELECT o
                FROM App\Entity\Octave o
                WHERE o.etat <> 0
                "
            )
            ->execute()
        ;
    }

    /**
    * @param $fournisseurId
    * return Octave[]
    */
    public function special($fournisseurId) : array
    {
        return $this->getEm()->createQuery(
                "SELECT o
                FROM App\Entity\Octave o
                WHERE o.fournisseurId = :fournisseurId AND
                    o.isSpecial = 1
                "
            )
            ->setParameter('fournisseurId', $fournisseurId)
            ->execute()
        ;
    }

    /**
    * @param $fournisseurId
    * return Octave[]
    */
    public function allSpecial() : array
    {
        return $this->getEm()->createQuery(
                "SELECT o
                FROM App\Entity\Octave o
                WHERE o.isSpecial = 1
                "
            )
            ->execute()
        ;
    }

    private function getEm()
    {
        $this->em = $this->getEntityManager();
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create($this->em->getConnection(), $this->em->getConfiguration());
        }
        return $this->em;
    }
}
