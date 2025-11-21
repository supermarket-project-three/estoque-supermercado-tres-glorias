<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\Produto;
use Application\Entity\MovimentoEstoque;

class IndexController extends AbstractActionController
{
    private $em; // EntityManager

    //Recebe o DoctrineService 
    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
    }

    public function indexAction()
    {
        
        // Pega o Repositório customizado de Produto
        $repoProduto = $this->em->getRepository(Produto::class);
        
        //Busca os dados para os KPIs
        
        $kpi_estoque_baixo = $repoProduto->getKpiItensEstoqueBaixo();
        $kpi_esgotados = $repoProduto->getKpiItensEsgotados();
        $kpi_total_produtos = $repoProduto->getKpiTotalProdutos();
        $kpi_valor_total = $repoProduto->getKpiValorTotalEstoque();
        
        //Busca os produtos para a tabela de Alertas
        $produtosEmAlerta = $repoProduto->findBy(
            ['ativo' => true], 
            ['quantidade' => 'ASC'],
            10
        );
        
        //Busca as últimas alterações
        $ultimasAlteracoes = $this->em->getRepository(MovimentoEstoque::class)
                                ->findBy([], ['data' => 'DESC'], 5);

        //Envia todos os dados para a View
        $viewModel = new ViewModel([
            'kpi_estoque_baixo' => $kpi_estoque_baixo,
            'kpi_esgotados' => $kpi_esgotados,
            'kpi_total_produtos' => $kpi_total_produtos,
            'kpi_valor_total' => $kpi_valor_total,
            'produtosEmAlerta' => $produtosEmAlerta,
            'ultimasAlteracoes' => $ultimasAlteracoes
        ]);
        
        $viewModel->setTerminal(true);
        
        return $viewModel;
    }
}