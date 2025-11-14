<?php
namespace Application\Controller;

    use Laminas\Mvc\Controller\AbstractActionController;
    use Laminas\View\Model\ViewModel;
    use Application\Service\DoctrineService;
    use Application\Entity\Usuario;
    use Laminas\Session\Container; 
    use Laminas\Http\Request;
class AuthController extends AbstractActionController
{
    private $em;

    public function __construct(DoctrineService $doctrineService)
    {
        $this->em = $doctrineService->getEntityManager();
    }

    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true); 
        return $viewModel;
    }

    public function autenticarAction()
    {
        // 1. Verifica se a requisição é um POST
        if ($this->getRequest()->getMethod() !== 'POST') {
            // Se não for, redireciona de volta para a página de login
            return $this->redirect()->toRoute('login');
        }

        // 2. Pega os dados do formulário
        $data = $this->params()->fromPost();
        $email = $data['email'] ?? null;
        $senhaPura = $data['senha'] ?? null;

        // 3. Busca o usuário no banco via Doctrine
        $usuario = $this->em->getRepository(Usuario::class)->findOneBy(['email' => $email]);

        // 4. Verifica se o usuário existe E se a senha está correta
        if ($usuario && $usuario->verificarSenha($senhaPura)) {
            
            // SUCESSO!
            // 5. Inicia a sessão PHP (usando o componente de Sessão do Laminas)
            $session = new Container('user'); // Cria um "espaço" de sessão chamado 'user'
            $session->id = $usuario->getId();
            $session->tipo = $usuario->getTipo();
            $session->nome = $usuario->getNome();

            // 6. Redireciona para a 'home' (/)
            // (Mais tarde, podemos criar a rota 'admin' e redirecionar para lá)
            return $this->redirect()->toRoute('home');
            
        } else {
            
            // FALHA!
            // 7. Redireciona de volta para o login com uma mensagem de erro
            return $this->redirect()->toRoute('login', ['error' => 'invalid']);
        }
    }
}