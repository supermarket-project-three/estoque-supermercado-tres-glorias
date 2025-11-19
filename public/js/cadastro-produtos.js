document.addEventListener('DOMContentLoaded', function() {

    // Elementos do DOM
    const formCadastro = document.getElementById('form-novo-produto');
    const boxFeedback = document.getElementById('feedback-sistema');

    /**
     * Função Genérica para Mostrar Notificações
     * @param {string} mensagem - O texto a ser exibido
     * @param {string} tipo - 'sucesso' ou 'erro'
     */
    function mostrarNotificacao(mensagem, tipo) {
        // Reseta classes anteriores
        boxFeedback.className = 'box-notificacao';
        
        // Define a classe de cor (verde ou vermelho)
        if (tipo === 'sucesso') {
            boxFeedback.classList.add('notificacao-sucesso');
        } else if (tipo === 'erro') {
            boxFeedback.classList.add('notificacao-erro');
        }

        // Define o texto
        boxFeedback.textContent = mensagem;

        // Mostra a caixa
        boxFeedback.style.display = 'block';

        // Opcional: Rolar a página até a notificação para o usuário ver
        boxFeedback.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Opcional: Esconder automaticamente após 5 segundos
        setTimeout(() => {
            boxFeedback.style.display = 'none';
        }, 5000);
    }


    // --- LÓGICA DE CADASTRO ---
    if (formCadastro) {
        formCadastro.addEventListener('submit', function(e) {
            e.preventDefault();

            // Coletar valores (apenas para simulação)
            const descricao = document.getElementById('prod_descricao').value;
            
            const simularErro = false;

            if (simularErro) {
                // CENÁRIO DE ERRO
                console.error("Erro ao cadastrar produto.");
                mostrarNotificacao(`Erro: Não foi possível cadastrar o produto "${descricao}". Tente novamente.`, 'erro');
            } else {
                // CENÁRIO DE SUCESSO
                console.log("Produto cadastrado com sucesso.");
                mostrarNotificacao(`Sucesso! O produto "${descricao}" foi cadastrado.`, 'sucesso');
                
                // Limpar formulário apenas no sucesso
                formCadastro.reset();
            }

        });
    }

});