<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\MovimentoEstoque;

class HistoricoController extends AbstractActionController
{
    private $em;

    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
    }

    public function indexAction()
    {
        // Captura os filtros do GET
        $tipo = $this->params()->fromQuery('tipo');
        $dataInicio = $this->params()->fromQuery('data_inicio');
        $dataFim = $this->params()->fromQuery('data_fim');
        $produto = $this->params()->fromQuery('produto');

        // Cria o QueryBuilder para buscas complexas
        $qb = $this->em->createQueryBuilder();
        $qb->select('m')
           ->from(MovimentoEstoque::class, 'm')
           ->join('m.produto', 'p')  // Junta com produto para buscar por nome
           ->orderBy('m.data', 'DESC'); // Mais recentes primeiro

        // Aplica os filtros se eles existirem
        if (!empty($tipo)) {
            $qb->andWhere('m.tipo = :tipo')
               ->setParameter('tipo', $tipo);
        }

        if (!empty($produto)) {
            $qb->andWhere('p.nome LIKE :produto OR p.marca LIKE :produto')
               ->setParameter('produto', '%' . $produto . '%');
        }

        if (!empty($dataInicio)) {
            $qb->andWhere('m.data >= :dataInicio')
               ->setParameter('dataInicio', $dataInicio . ' 00:00:00');
        }

        if (!empty($dataFim)) {
            $qb->andWhere('m.data <= :dataFim')
               ->setParameter('dataFim', $dataFim . ' 23:59:59');
        }

        // Executa a consulta
        $movimentacoes = $qb->getQuery()->getResult();

        // Envia para a view
        $viewModel = new ViewModel([
            'movimentacoes' => $movimentacoes,
            // Passamos os filtros de volta para preencher o formulário
            'filtro' => [
                'tipo' => $tipo,
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'produto' => $produto
            ]
        ]);
        
        $viewModel->setTerminal(true);
        return $viewModel;
    }
}