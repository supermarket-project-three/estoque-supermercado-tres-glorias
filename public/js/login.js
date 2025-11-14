/*// Espera o documento HTML carregar antes de executar o script
document.addEventListener('DOMContentLoaded', function() {

    
    const msgErro = document.getElementById('msgErro');
    
    
    // Adiciona um "escutador de clique" no botão de login
    botaoLogin.addEventListener('click', function() {
        
        // Pega os valores que o usuário digitou
        const usuarioDigitado = campoUsuario.value;
        const senhaDigitada = campoSenha.value;

        // *** A LÓGICA MESTRE (APENAS PARA MAQUETE) ***
        // (No futuro, o PHP vai checar isso no JSON)
        
        // Login e Senha do Administrador
        const adminUser = 'admin';
        const adminPass = 'admin';

        // Login e Senha do Funcionário
        const funcUser = 'funcionario';
        const funcPass = '12345';

        // 5. Verifica o tipo de usuário
        if (usuarioDigitado === adminUser && senhaDigitada === adminPass) {
            
            // SUCESSO (ADMIN)
            msgErro.textContent = ''; 
            botaoLogin.textContent = 'Carregando...';
            
            // Redireciona para o painel do ADMIN
            window.location.href = 'dashboard.html'; 

        } else if (usuarioDigitado === funcUser && senhaDigitada === funcPass) {

            // SUCESSO (FUNCIONÁRIO)
            msgErro.textContent = ''; 
            botaoLogin.textContent = 'Carregando...';

            // Redireciona para a página de ESTOQUE
            window.location.href = 'estoque.html';

        } else {
            
            // FALHA (Usuário ou senha errados)
            msgErro.textContent = 'Usuário ou senha inválidos.';
        }
    });*/

    //Funcionalidade do olho de mostrar senha
    
    //Mostrar ou ocultar senha 
    const botaoLogin = document.getElementById('btLogin');
    const campoUsuario = document.getElementById('inUser');
    const campoSenha = document.getElementById('inPassword');
    const btOlhoAberto = document.getElementById("btOlhoAberto");
    const btOlhoFechado = document.getElementById("btOlhoFechado");

    function MudarOlho(){

        if(campoSenha.type == "password"){
            btOlhoFechado.className = "naoMostrar";
            btOlhoAberto.className = "mostrar";
            campoSenha.type = "text";
        }
        else if(campoSenha.type == "text"){
            btOlhoAberto.className = "naoMostrar";
            btOlhoFechado.className = "mostrar";
            campoSenha.type = "password";
        }
    }

    btOlhoAberto.addEventListener("click", MudarOlho);
    btOlhoFechado.addEventListener("click", MudarOlho);

//});