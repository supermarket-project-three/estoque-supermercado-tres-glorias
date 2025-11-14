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
        return new ViewModel([
            'usuarios' => $usuarios,
            'setores'  => $setores // <<< NOVO
        ]);
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

        // --- INÍCIO DA LÓGICA DE SETOR ---
        // Se for 'responsavel' E um setor foi enviado
        if ($data['tipo'] === 'responsavel' && !empty($data['setor_id'])) {
            // Busca o objeto Setor no banco
            $setorObj = $this->em->getRepository(Setor::class)->find($data['setor_id']);
            
            if ($setorObj) {
                // Associa o Setor ao Usuário
                $novoUsuario->setSetor($setorObj); 
            }
        }
        // --- FIM DA LÓGICA DE SETOR ---

        $this->em->persist($novoUsuario);
        $this->em->flush();

        return $this->redirect()->toRoute('dashboard-usuarios');
    }
}