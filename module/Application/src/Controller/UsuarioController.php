<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\Usuario;
use Application\Entity\Setor;

class UsuarioController extends AbstractActionController
{
    private $em; // EntityManager

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
        // Busca todos os usuários
        $usuarios = $this->em->getRepository(Usuario::class)->findAll();
        
        //Busca todos os setores
        $setores = $this->em->getRepository(Setor::class)->findAll();

        //Envia usuários E setores para a view
        $viewModel = new ViewModel([
            'usuarios' => $usuarios,
            'setores'  => $setores
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

            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        $novoUsuario = new Usuario();
        $novoUsuario->setNome($data['nome']);
        $novoUsuario->setEmail($data['email']);
        $novoUsuario->setTipo($data['tipo']);
        $novoUsuario->setSenhaComHash($data['senha']);

        // Se for responsavel e um setor foi enviado
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

    //Função de apagar usuário
    public function apagarAction()
    {
        // Pega o ID da rota (do URL)
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id === 0) {
            // Se não houver ID, volta
            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        try {
            // Encontra o usuário no banco
            $usuario = $this->em->getRepository(Usuario::class)->find($id);

            if ($usuario) {
                //Remove o usuário
                $this->em->remove($usuario);
                $this->em->flush();
            }
        } catch (\Exception $e) {
            echo 'erro: ' . $e->getMessage();
        }

        // Redireciona de volta para a lista
        return $this->redirect()->toRoute('dashboard-usuarios');
    }

    /**
     * Ação GET /dashboard-usuarios/editar/:id
     * Mostra o formulário preenchido com os dados do usuário
     */
    public function editarAction()
    {
        // Pega o ID da rota
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        // Busca o usuário no banco
        $usuario = $this->em->getRepository(Usuario::class)->find($id);
        if (!$usuario) {
            return $this->redirect()->toRoute('dashboard-usuarios');
        }
        
        // Busca os setores para o dropdown
        $setores = $this->em->getRepository(Setor::class)->findAll();

        // Envia os dados para uma nova view
        $viewModel = new ViewModel([
            'usuario' => $usuario, // O usuário a ser editado
            'setores' => $setores   // A lista de setores
        ]);
        
        // Aponta para o novo arquivo de view
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
        // Pega o ID da rota
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $usuario = $this->em->getRepository(Usuario::class)->find($id);

        if (!$usuario || $this->getRequest()->getMethod() !== 'POST') {
            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        // Pega os dados do formulário
        $data = $this->params()->fromPost();

        // Atualiza o objeto Usuário
        $usuario->setNome($data['nome']);
        $usuario->setEmail($data['email']);
        $usuario->setTipo($data['tipo']);

        if (!empty($data['senha'])) {
            $usuario->setSenhaComHash($data['senha']);
        }

        // Lógica do Setor
        if ($data['tipo'] === 'responsavel' && !empty($data['setor_id'])) {
            $setorObj = $this->em->getRepository(Setor::class)->find($data['setor_id']);
            if ($setorObj) {
                $usuario->setSetor($setorObj);
            }
        } else {
            // Se for admin, remove a associação de setor
            $usuario->setSetor(null); 
        }

        // Salva as alterações no banco
        $this->em->flush();

        // Redireciona de volta para a lista
        return $this->redirect()->toRoute('dashboard-usuarios');
    }
}