<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\DoctrineService;
use Application\Entity\Usuario;

class UsuarioController extends AbstractActionController
{
    private $em; // Nosso EntityManager

    // 1. Recebemos o DoctrineService (igual ao AuthController)
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
        // 1. Busca todos os usuários existentes no banco
        $usuarios = $this->em->getRepository(Usuario::class)->findAll();

        // 2. Envia os usuários para a view
        return new ViewModel([
            'usuarios' => $usuarios
        ]);
    }

    /**
     * Ação POST /dashboard-usuarios/salvar
     * Processa o formulário de cadastro
     */
    public function salvarAction()
    {
        // Verifica se é um POST
        if ($this->getRequest()->getMethod() !== 'POST') {
            // Se não for POST, volta para a listagem
            return $this->redirect()->toRoute('dashboard-usuarios'); // <<< CORRIGIDO
        }

        // Pega os dados do formulário
        $data = $this->params()->fromPost();

        // --- (Validação: verificar se o email já existe) ---
        $emailExistente = $this->em->getRepository(Usuario::class)->findOneBy(['email' => $data['email']]);
        if ($emailExistente) {
            // Se o email já existe, volta

            return $this->redirect()->toRoute('dashboard-usuarios');
        }

        // Cria a nova entidade Usuario
        $novoUsuario = new Usuario();
        $novoUsuario->setNome($data['nome']);
        $novoUsuario->setEmail($data['email']);
        $novoUsuario->setTipo($data['tipo']); // 'admin' ou 'responsavel'
        $novoUsuario->setSenhaComHash($data['senha']); // Usa o método que gera o HASH

        // Salva no banco
        $this->em->persist($novoUsuario);
        $this->em->flush();

        // Redireciona de volta para a página de usuários
        return $this->redirect()->toRoute('dashboard-usuarios');
    }
}