<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\Setor;

class SetorController extends AbstractActionController
{
    private $em; //EntityManager

    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
    }

    /**
     * Ação GET /dashboard-setores
     * Mostra o formulário e a lista de setores
     */
    public function indexAction()
    {
        //Busca todos os setores do banco
        $setores = $this->em->getRepository(Setor::class)->findAll();

        //Cria a ViewModel
        $viewModel = new ViewModel([
            'setores' => $setores // Envia os setores para a view
        ]);

        $viewModel->setTerminal(true);

        return $viewModel;
    }

    /**
     * Ação POST /dashboard-setores/salvar
     * Processa o formulário de cadastro de setor
     */
    public function salvarAction()
    {
        if ($this->getRequest()->getMethod() !== 'POST') {
            return $this->redirect()->toRoute('dashboard-setores');
        }

        $data = $this->params()->fromPost();

        // Cria a nova entidade Setor
        $novoSetor = new Setor();
        $novoSetor->setNome($data['nome_setor']);

        // Salva no banco
        $this->em->persist($novoSetor);
        $this->em->flush();

        // Redireciona de volta para a lista de setores
        return $this->redirect()->toRoute('dashboard-setores');
    }

    /**
     * Ação GET /dashboard-setores/apagar/:id
     * Remove um setor do banco
     */
    public function apagarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        try {
            $setor = $this->em->getRepository(Setor::class)->find($id);
            if ($setor) {
                $this->em->remove($setor);
                $this->em->flush();
            }
        } catch (\Exception $e) {
            echo "ERRO: " . $e->getMessage();
        }

        return $this->redirect()->toRoute('dashboard-setores');
    }
}