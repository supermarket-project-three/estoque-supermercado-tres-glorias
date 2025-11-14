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

    /**
     * Ação GET /dashboard-usuarios/editar/:id
     * Mostra o formulário preenchido com os dados do usuário
     */
    public function editarAction()
    {
        // 1. Pega o ID da rota
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        // 2. Busca o usuário no banco
        $usuario = $this->em->getRepository(Usuario::class)->find($id);
        if (!$usuario) {
            return $this->redirect()->toRoute('dashboard-usuarios');
        }
        
        // 3. Busca os setores (para o dropdown)
        $setores = $this->em->getRepository(Setor::class)->findAll();

        // 4. Envia os dados para uma nova view (editar.phtml)
        $viewModel = new ViewModel([
            'usuario' => $usuario, // O usuário a ser editado
            'setores' => $setores   // A lista de setores
        ]);
        
        // Aponta para o novo arquivo de view que vamos criar
        $viewModel->setTemplate('application/usuario/editar.phtml'); 
        
        $viewModel->setTerminal(true); 
        
        return $viewModel;
    }

    /**
     * Ação POST /dashboard-usuarios/atualizar/:id
     * Recebe os dados do formulário de edição e salva
     */
    public function atualizarAction()
    {
        // 1. Pega o ID da rota
        $id = (int) $this->params()->fromRoute('id', 0);
        
        // 2. Busca o usuário que queremos ATUALIZAR
        $usuario = $this->em->getRepository(Usuario::class)->find($id);

        if (!$usuario || $this->getRequest()->getMethod() !== 'POST') {
            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        // 3. Pega os dados do formulário
        $data = $this->params()->fromPost();

        // 4. Atualiza o objeto Usuário
        $usuario->setNome($data['nome']);
        $usuario->setEmail($data['email']);
        $usuario->setTipo($data['tipo']);

        // 5. Lógica da Senha: Só atualiza se uma NOVA senha for digitada
        if (!empty($data['senha'])) {
            $usuario->setSenhaComHash($data['senha']);
        }

        // 6. Lógica do Setor
        if ($data['tipo'] === 'responsavel' && !empty($data['setor_id'])) {
            $setorObj = $this->em->getRepository(Setor::class)->find($data['setor_id']);
            if ($setorObj) {
                $usuario->setSetor($setorObj);
            }
        } else {
            // Se for admin, remove a associação de setor
            $usuario->setSetor(null); 
        }

        // 7. Salva as alterações no banco
        // (Não é preciso persist(), pois o objeto já está "gerido" pelo Doctrine)
        $this->em->flush();

        // 8. Redireciona de volta para a lista
        return $this->redirect()->toRoute('dashboard-usuarios');
    }
}