<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\Setor;

class SetorController extends AbstractActionController
{
    private $em; // Nosso EntityManager

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
        // 1. Busca todos os setores do banco
        $setores = $this->em->getRepository(Setor::class)->findAll();

        // 2. Cria a ViewModel
        $viewModel = new ViewModel([
            'setores' => $setores // Envia os setores para a view
        ]);

        // 3. Diz ao Laminas para NÃO USAR o layout principal (barra azul)
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
        
        // (Opcional: verificar se o setor já existe)

        // Cria a nova entidade Setor
        $novoSetor = new Setor();
        $novoSetor->setNome($data['nome_setor']);
        // (O seu 'Setor' não precisa de 'responsavel_id' no cadastro,
        // isso será feito na edição do 'Usuário')

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
                // (Cuidado: Adicionar lógica para verificar se há produtos
                // ou usuários ligados a este setor antes de apagar)
                $this->em->remove($setor);
                $this->em->flush();
            }
        } catch (\Exception $e) {
            // (Lidar com o erro de chave estrangeira)
        }

        return $this->redirect()->toRoute('dashboard-setores');
    }
}