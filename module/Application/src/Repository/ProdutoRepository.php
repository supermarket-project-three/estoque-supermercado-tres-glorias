<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Produto;


class ProdutoRepository extends EntityRepository
{
    /**
     * Calcula o total de produtos cadastrados.
     */
    public function getKpiTotalProdutos()
    {
        $dql = 'SELECT COUNT(p.id) FROM Application\Entity\Produto p';
        
        return $this->getEntityManager()->createQuery($dql)
                    ->getSingleScalarResult();
    }

    /**
     * Calcula o total de produtos com estoque zero.
     */
    public function getKpiItensEsgotados()
    {
        $dql = 'SELECT COUNT(p.id) FROM Application\Entity\Produto p 
                WHERE p.quantidade <= 0';
        
        return $this->getEntityManager()->createQuery($dql)
                    ->getSingleScalarResult();
    }

    /**
     * Calcula itens com estoque baixo (acima de 0, mas abaixo do mínimo)
     */
    public function getKpiItensEstoqueBaixo()
    {
        // Esta consulta compara o estoque atual com o estoque mínimo
        $dql = 'SELECT COUNT(p.id) FROM Application\Entity\Produto p 
                WHERE p.quantidade > 0 
                AND p.quantidade <= 10';
        
        return $this->getEntityManager()->createQuery($dql)
                    ->getSingleScalarResult(); // Retorna (ex: 5)
    }

    /**
     * Calcula o valor total do estoque
     */
    public function getKpiValorTotalEstoque()
    {
        $dql = 'SELECT SUM(p.preco * p.quantidade) FROM Application\Entity\Produto p';
        
        $valor = $this->getEntityManager()->createQuery($dql)
                      ->getSingleScalarResult();
        
        return $valor ?? 0;
    }
}