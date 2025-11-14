<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Produto;

// Estendemos o EntityRepository padrão
class ProdutoRepository extends EntityRepository
{
    /**
     * Calcula o total de produtos cadastrados.
     */
    public function getKpiTotalProdutos()
    {
        // DQL (Doctrine Query Language) - parece SQL, mas usa objetos
        $dql = 'SELECT COUNT(p.id) FROM Application\Entity\Produto p';
        
        return $this->getEntityManager()->createQuery($dql)
                    ->getSingleScalarResult(); // Retorna um único valor (ex: 132)
    }

    /**
     * Calcula o total de produtos com estoque zero.
     */
    public function getKpiItensEsgotados()
    {
        $dql = 'SELECT COUNT(p.id) FROM Application\Entity\Produto p 
                WHERE p.estoqueAtual <= 0';
        
        return $this->getEntityManager()->createQuery($dql)
                    ->getSingleScalarResult(); // Retorna (ex: 1)
    }

    /**
     * Calcula itens com estoque baixo (acima de 0, mas abaixo do mínimo)
     */
    public function getKpiItensEstoqueBaixo()
    {
        // Esta consulta compara o estoque atual com o estoque mínimo
        $dql = 'SELECT COUNT(p.id) FROM Application\Entity\Produto p 
                WHERE p.estoqueAtual > 0 
                AND p.estoqueAtual <= p.estoqueMinimo';
        
        return $this->getEntityManager()->createQuery($dql)
                    ->getSingleScalarResult(); // Retorna (ex: 5)
    }

    /**
     * Calcula o valor total do estoque (Preço * Quantidade)
     */
    public function getKpiValorTotalEstoque()
    {
        // SUM (soma) da multiplicação do preço pela quantidade
        $dql = 'SELECT SUM(p.preco * p.estoqueAtual) FROM Application\Entity\Produto p';
        
        $valor = $this->getEntityManager()->createQuery($dql)
                      ->getSingleScalarResult();
        
        return $valor ?? 0; // Retorna 0 se o SUM for nulo (nenhum produto)
    }
}