<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\Produto;
use Application\Entity\Setor;

class ProdutoController extends AbstractActionController
{
    private $em; 

    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
    }

    public function indexAction()
    {
        $termoPesquisa = $this->params()->fromQuery('q', null);
        $filtroSetor = $this->params()->fromQuery('filtro_setor', null);

        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
           ->from(Produto::class, 'p')
           ->orderBy('p.nome', 'ASC');

        if (!empty($termoPesquisa)) {
            $qb->andWhere('p.nome LIKE :termo OR p.marca LIKE :termo')
               ->setParameter('termo', '%' . $termoPesquisa . '%');
        }

        if (!empty($filtroSetor)) {
            $qb->andWhere('p.setor = :setorId')
               ->setParameter('setorId', $filtroSetor);
        }

        $produtos = $qb->getQuery()->getResult();
        $todosSetores = $this->em->getRepository(Setor::class)->findAll();

        $viewModel = new ViewModel([
            'produtos'      => $produtos,
            'setores'       => $todosSetores,
            'termoPesquisa' => $termoPesquisa, 
            'filtroSetor'   => $filtroSetor    
        ]);
        
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function salvarAction()
    {
        if ($this->getRequest()->getMethod() !== 'POST') {
            return $this->redirect()->toRoute('dashboard-produtos');
        }

        $data = $this->params()->fromPost();
        
        // Cria o Produto
        $produto = new Produto();
        $produto->setNome($data['nome']);
        $produto->setDescricao($data['descricao'] ?? null);
        $produto->setMarca($data['marca']);
        
        // Preço e Quantidade 
        $produto->setPreco((float) $data['preco']);
        $produto->setQuantidade(0); // Sempre começa com 0
        
        // Associa ao Setor
        $setor = $this->em->getRepository(Setor::class)->find($data['setor_id']);
        if ($setor) {
            $produto->setSetor($setor);
        }

        // Salva
        $this->em->persist($produto);
        $this->em->flush();

        $this->flashMessenger()->addSuccessMessage('Produto cadastrado com sucesso!');

        return $this->redirect()->toRoute('dashboard-produtos');
    }

    /**
     * Ação GET /dashboard-produtos/editar/:id
     */
    public function editarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('dashboard-produtos');
        }

        // Busca o produto e os setores (para o dropdown)
        $produto = $this->em->getRepository(Produto::class)->find($id);
        $setores = $this->em->getRepository(Setor::class)->findAll();

        if (!$produto) {
            return $this->redirect()->toRoute('dashboard-produtos');
        }

        $viewModel = new ViewModel([
            'produto' => $produto,
            'setores' => $setores
        ]);
        
        // Define explicitamente qual arquivo de view usar
        $viewModel->setTemplate('application/produto/editar.phtml');
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    /**
     * Ação POST /dashboard-produtos/atualizar/:id
     */
    public function atualizarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id || $this->getRequest()->getMethod() !== 'POST') {
            return $this->redirect()->toRoute('dashboard-produtos');
        }

        $produto = $this->em->getRepository(Produto::class)->find($id);
        if (!$produto) {
            return $this->redirect()->toRoute('dashboard-produtos');
        }

        $data = $this->params()->fromPost();

        // Atualiza apenas dados cadastrais
        $produto->setNome($data['nome']);
        $produto->setDescricao($data['descricao']);
        $produto->setMarca($data['marca']);
        $produto->setPreco((float) $data['preco']);

        // Atualiza o setor
        $setor = $this->em->getRepository(Setor::class)->find($data['setor_id']);
        if ($setor) {
            $produto->setSetor($setor);
        }

        $this->em->flush();

        $this->flashMessenger()->addSuccessMessage('Produto atualizado com sucesso!');

        return $this->redirect()->toRoute('dashboard-produtos');
    }
}