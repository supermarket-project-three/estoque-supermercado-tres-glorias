<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Laminas\Session\Container;
use Application\Entity\Setor;

class EstoqueController extends AbstractActionController
{
    private $em;

    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
    }

    /**
     * Ação GET /estoque
     * Mostra a lista de setores e seus produtos
     */
    public function indexAction()
    {
        $session = new Container('user');
        
        //Busca todos os setores (O Doctrine traz os produtos junto)
        $setores = $this->em->getRepository(Setor::class)->findAll();

        $viewModel = new ViewModel([
            'nomeUsuario' => $session->nome,
            'setores'     => $setores // Envia a lista para a view
        ]);

        // Desativa o layout padrão (barra azul) pois sua página é completa
        $viewModel->setTerminal(true);
        
        return $viewModel;
    }
    
    // (Futuramente adicionaremos a ação para salvar as quantidades aqui)
}