<?php

namespace AppBundle\Repository;

/**
 * RevistaTextoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RevistaTextoRepository extends \Doctrine\ORM\EntityRepository
{
    public function buscarPorRevistaTexto($revista,$texto)
    {
        return $this->getEntityManager()

            ->createQuery("SELECT rt 
                                  from AppBundle:RevistaTexto rt 
                                   WHERE rt.texto = :texto
                                   and rt.revista = :revista
                                   ")


            ->setParameters(array(
                'texto' => $texto,
                'revista' => $revista,
            ))

            ->getOneOrNullResult() ;
    }

    public function numeroNuevosTexto($revista)
    {
        return $this->getEntityManager()

            ->createQuery("SELECT count(rt) 
                                  from AppBundle:RevistaTexto rt 
                                   WHERE rt.visto = FALSE 
                                   and rt.revista = :revista
                                   ")


            ->setParameters(array(

                'revista' => $revista,
            ))

            ->getSingleScalarResult();
    }
}
