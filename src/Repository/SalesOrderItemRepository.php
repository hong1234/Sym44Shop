<?php

namespace App\Repository;

/**
 * SalesOrderItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SalesOrderItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function getBestsellers()
    {
        $products = array();

        $query = $this->_em->createQuery('SELECT IDENTITY(t.product), SUM(t.qty) AS HIDDEN q
                        FROM App\Entity\SalesOrderItem t
                        GROUP BY t.product ORDER BY q DESC')
                        ->setMaxResults(5);

        $_products = $query->getResult();

        foreach ($_products as $_product) {
            $products[] = $this->_em->getRepository('Product')
                                ->find(current($_product));
        }

        return $products;
    }
}
