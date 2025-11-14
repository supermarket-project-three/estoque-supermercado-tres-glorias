<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\Produto; // Importa a entidade Produto
use Application\Entity\MovimentoEstoque; // Importa a entidade MovimentoEstoque

class IndexController extends AbstractActionController
{
    private $em; // EntityManager do Doctrine

    // 1. Recebe o DoctrineService (Correto)
    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
    }

    public function indexAction()
    {
        // --- LÓGICA DO DASHBOARD (AGORA 100% DINÂMICA) ---
        
        // 2. Pega o Repositório customizado de Produto
        // (O Doctrine sabe que é o ProdutoRepository graças ao mapeamento na Entidade)
        $repoProduto = $this->em->getRepository(Produto::class);
        
        // 3. <<< ALTERAÇÃO: Busca os dados para os KPIs (Cartões) >>>
        // Substitui os valores estáticos pelas chamadas reais ao repositório
        
        $kpi_estoque_baixo = $repoProduto->getKpiItensEstoqueBaixo();
        $kpi_esgotados = $repoProduto->getKpiItensEsgotados();
        $kpi_total_produtos = $repoProduto->getKpiTotalProdutos();
        $kpi_valor_total = $repoProduto->getKpiValorTotalEstoque();
        
        // 4. Busca os produtos para a tabela de "Alertas" (O seu código já fazia isto)
        $produtosEmAlerta = $repoProduto->findBy(
            ['ativo' => true], // Critérios
            ['estoqueAtual' => 'ASC'], // Ordenar (estoque baixo primeiro)
            10 // Limite
        );
        
        // 5. Busca as últimas alterações
        // (O seu código já fazia isto, continuará vazio até implementarmos MovimentoEstoque)
        $ultimasAlteracoes = $this->em->getRepository(MovimentoEstoque::class)
                                ->findBy([], ['data' => 'DESC'], 5);

        // 6. Envia todos os dados REAIS para a View
        $viewModel = new ViewModel([
            'kpi_estoque_baixo' => $kpi_estoque_baixo,
            'kpi_esgotados' => $kpi_esgotados,
            'kpi_total_produtos' => $kpi_total_produtos,
            'kpi_valor_total' => $kpi_valor_total,
            'produtosEmAlerta' => $produtosEmAlerta,
            'ultimasAlteracoes' => $ultimasAlteracoes
        ]);
        
        // Diz ao Laminas para NÃO USAR o layout principal (barra azul)
        $viewModel->setTerminal(true);
        
        return $viewModel;
    }
}