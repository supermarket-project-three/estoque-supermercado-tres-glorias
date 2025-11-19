document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Selecionar Elementos
    const modal = document.getElementById('modalMovimentacao');
    const btnFechar = document.getElementById('btnFecharModal');
    const btnsMovimentar = document.querySelectorAll('.btn-movimentar');
    
    // Elementos do Formulário
    const inputId = document.getElementById('modalIdProduto');
    const spanNome = document.getElementById('modalNomeProduto');
    const inputQtd = document.getElementById('modalQtd');
    const inputObs = document.getElementById('modalObs');

    // Verificação de segurança (opcional, ajuda no debug)
    if (!modal) {
        console.error("Modal não encontrado! Verifique o ID 'modalMovimentacao' no HTML.");
        return;
    }

    // 2. Abrir Modal ao clicar em "Movimentar"
    btnsMovimentar.forEach(btn => {
        btn.addEventListener('click', function() {
            // Pega os dados do botão clicado
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');
            
            // Preenche o modal
            inputId.value = id;
            spanNome.textContent = nome;
            
            // Limpa os campos
            inputQtd.value = '';
            inputObs.value = '';

            // Mostra o modal (usando flex para centralizar)
            modal.style.display = 'flex';
            
            // Foca no campo quantidade
            setTimeout(() => inputQtd.focus(), 100);
        });
    });

    // 3. Fechar Modal (Botão Cancelar)
    if (btnFechar) {
        btnFechar.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    // 4. Fechar Modal (Clicar fora da caixa)
    window.addEventListener('click', function(e) {
        if (e.target == modal) {
            modal.style.display = 'none';
        }
    });
});