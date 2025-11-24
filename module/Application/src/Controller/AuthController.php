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
        $error = $this->params()->fromQuery('error', null);

        $viewModel = new ViewModel([
            'error' => $error 
        ]);
        $viewModel->setTerminal(true); 
        return $viewModel;
    }

    public function autenticarAction()
    {
        
        //Verifica se a requisição é um POST
        if ($this->getRequest()->getMethod() !== 'POST') {
            // Se não for, redireciona de volta para a página de login
            return $this->redirect()->toRoute('login');
        }

        //Pega os dados do formulário
        $data = $this->params()->fromPost();
        $email = $data['email'] ?? null;
        $senhaPura = $data['senha'] ?? null;

        //Busca o usuário no banco
        $usuario = $this->em->getRepository(Usuario::class)->findOneBy(['email' => $email]);

        //Verifica se o usuário existe E se a senha está correta
        if ($usuario && $usuario->verificarSenha($senhaPura)) {
            
            // Inicia a sessão PHP (usando Sessão do Laminas)
            $session = new Container('user'); // Cria sessão chamada user
            $session->id = $usuario->getId();
            $session->tipo = $usuario->getTipo();
            $session->nome = $usuario->getNome();

            //Redireciona para o inicio
            if ($usuario->getTipo() === 'admin') {
            //Se for Admin, vai para o dashboard
            return $this->redirect()->toRoute('dashboard');
            } else {
                //Se for Responsável, vai para o estoque
                return $this->redirect()->toRoute('estoque');
            }
            
        } else {
            
            // Redireciona de volta para o login com uma mensagem de erro
            // Obtém o URL da rota 'login'
            $url = $this->url()->fromRoute('login');
            
            $url .= '?error=invalid';
            
            return $this->redirect()->toUrl($url);
        }
    }

    /**
     * Ação /logout
     * Destrói a sessão e redireciona para o login
     */
    public function logoutAction()
    {
        // Pega o container da sessão user
        $session = new Container('user');
        
        // Destrói os dados da sessão
        $session->getManager()->destroy();

        // Redireciona para a página de login
        return $this->redirect()->toRoute('login');
    }
}