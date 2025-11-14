<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\Usuario;
use Application\Entity\Setor; // <<< ADICIONE ESTE 'USE'

class UsuarioController extends AbstractActionController
{
    private $em; // Nosso EntityManager

    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
    }

    /**
     * Ação GET /dashboard-usuarios
     * Mostra o formulário e a lista de usuários
     */
    public function indexAction()
    {
        // 1. Busca todos os usuários
        $usuarios = $this->em->getRepository(Usuario::class)->findAll();
        
        // 2. <<< NOVO: Busca todos os setores para o dropdown >>>
        $setores = $this->em->getRepository(Setor::class)->findAll();

        // 3. Envia usuários E setores para a view
        $viewModel = new ViewModel([
            'usuarios' => $usuarios,
            'setores'  => $setores // <<< NOVO
        ]);

        $viewModel->setTerminal(true);

        return $viewModel;
    }

    /**
     * Ação POST /dashboard-usuarios/salvar
     * Processa o formulário de cadastro
     */
    public function salvarAction()
    {
        if ($this->getRequest()->getMethod() !== 'POST') {
            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        $data = $this->params()->fromPost();

        $emailExistente = $this->em->getRepository(Usuario::class)->findOneBy(['email' => $data['email']]);
        if ($emailExistente) {
            // (Futuramente, adicionaremos uma mensagem de erro flash)
            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        $novoUsuario = new Usuario();
        $novoUsuario->setNome($data['nome']);
        $novoUsuario->setEmail($data['email']);
        $novoUsuario->setTipo($data['tipo']);
        $novoUsuario->setSenhaComHash($data['senha']);

        // Se for 'responsavel' E um setor foi enviado
        if ($data['tipo'] === 'responsavel' && !empty($data['setor_id'])) {
            // Busca o objeto Setor no banco
            $setorObj = $this->em->getRepository(Setor::class)->find($data['setor_id']);
            
            if ($setorObj) {
                // Associa o Setor ao Usuário
                $novoUsuario->setSetor($setorObj); 
            }
        }

        $this->em->persist($novoUsuario);
        $this->em->flush();

        return $this->redirect()->toRoute('dashboard-usuarios');
    }

    public function apagarAction()
    {
        // 1. Pega o ID da rota (do URL)
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id === 0) {
            // Se não houver ID, volta
            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        try {
            // 2. Encontra o usuário no banco
            $usuario = $this->em->getRepository(Usuario::class)->find($id);

            if ($usuario) {
                // 3. Remove o usuário
                $this->em->remove($usuario);
                $this->em->flush(); // Aplica a remoção no banco
            }
        } catch (\Exception $e) {
            echo 'erro: ' . $e->getMessage();
        }

        // 4. Redireciona de volta para a lista
        return $this->redirect()->toRoute('dashboard-usuarios');
    }
}