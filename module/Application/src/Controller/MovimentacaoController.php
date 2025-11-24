<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\Produto;
use Application\Entity\MovimentoEstoque;
use Application\Entity\Usuario;
use Laminas\Session\Container;

class MovimentacaoController extends AbstractActionController
{
    private $em;

    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
    }

    public function indexAction()
    {
        // Busca todos os produtos para o dropdown
        $produtos = $this->em->getRepository(Produto::class)->findBy([], ['nome' => 'ASC']);
        $produtoId = (int) $this->params()->fromQuery('produto_id', 0);

        $viewModel = new ViewModel([
            'produtos' => $produtos,
            'produtoSelecionado' => $produtoId
        ]);
        
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function salvarAction()
    {

        if ($this->getRequest()->getMethod() !== 'POST') {
            return $this->redirect()->toRoute('movimentacao');
        }

        $data = $this->params()->fromPost();
        
        
        $produto = $this->em->getRepository(Produto::class)->find($data['produto_id']);
        $qtd = (int) $data['quantidade'];
        $tipo = $data['tipo'];
        
        $session = new Container('user');
        $usuario = $this->em->getRepository(Usuario::class)->find($session->id);

        if (!$produto || !$usuario || $qtd <= 0) {
            return $this->redirect()->toRoute('movimentacao');
        }

        // Atualiza Estoque
        $estoqueAtual = $produto->getQuantidade();

        if ($tipo === 'entrada') {
            $produto->setQuantidade($estoqueAtual + $qtd);
        } elseif ($tipo === 'saida') {
            if ($estoqueAtual < $qtd) {
                return $this->redirect()->toRoute('movimentacao'); 
            }
            $produto->setQuantidade($estoqueAtual - $qtd);
        }

        // Cria Histórico
        $movimento = new MovimentoEstoque();
        $movimento->setProduto($produto);
        $movimento->setUsuario($usuario);
        $movimento->setQuantidade($qtd);
        $movimento->setTipo($tipo);
        $movimento->setData(new \DateTime());
        
        //Salva a mensagem/observação
        $observacao = $data['observacao'] ?? null;
        $movimento->setObservacao($observacao);

        $this->em->persist($movimento);
        $this->em->flush();

        $this->flashMessenger()->addSuccessMessage('Movimentação registrada com sucesso!');
        
        if ($usuario->getTipo() === 'admin') {
            // Se for Admin, volta para a gestão de produtos
            return $this->redirect()->toRoute('dashboard-produtos');
        } else {
            // Se for Responsável, volta para o painel do seu setor
            return $this->redirect()->toRoute('estoque');
        }
    }
}