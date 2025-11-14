/*
  'DOMContentLoaded' garante que este código só rode
  depois que todo o HTML da página foi carregado.
*/
document.addEventListener('DOMContentLoaded', function() {

    // --- 1. LÓGICA PARA CADASTRAR NOVO SETOR ---
    
    // Pega o formulário pelo ID que colocamos no HTML
    const formNovoSetor = document.getElementById('form-novo-setor');
    
    if (formNovoSetor) {
        formNovoSetor.addEventListener('submit', function(event) {
            // Previne o envio padrão do formulário (que recarrega a página)
            event.preventDefault(); 
            
            // Pega o <input> e o nome do setor
            const inputNome = document.getElementById('nome_setor');
            const nomeSetor = inputNome.value.trim();

            if (nomeSetor === "") {
                alert("Por favor, digite o nome do setor.");
                return;
            }

            // SIMULAÇÃO DE AJAX (FETCH)
            console.log("Enviando para o PHP (via fetch):", nomeSetor);

            const idSimulado = Math.floor(Math.random() * 1000);
            adicionarSetorNaLista(idSimulado, nomeSetor);
            inputNome.value = ''; // Limpa o campo

        });
    }


    // --- 2. LÓGICA PARA EXCLUIR SETOR ---

    // Pega a lista <ul> pelo ID
    const listaSetores = document.getElementById('lista-de-setores');

    if (listaSetores) {
        // Usa "Event Delegation": ouve o clique na LISTA inteira
        listaSetores.addEventListener('click', function(event) {
            
            // Verifica se o que clicamos foi um botão de excluir
            if (event.target.classList.contains('js-btn-excluir')) {
                event.preventDefault(); // Previne a ação do link '#'
                
                // Pede confirmação (agora sim, no JS)
                if (confirm("Tem certeza que deseja excluir este setor?")) {
                    
                    // Pega o <li> pai do botão
                    const itemLista = event.target.closest('.lista-item');
                    
                    // Pega o ID que colocamos no data-setor-id
                    const idSetor = itemLista.dataset.setorId;

                    // SIMULAÇÃO DE AJAX (FETCH)
                    console.log("Excluindo do PHP (via fetch):", idSetor);

                    itemLista.remove();
                }
            }
        });
    }

}); // Fim do DOMContentLoaded


/**
 * Função helper para adicionar um novo setor na <ul>
 */
function adicionarSetorNaLista(id, nome) {
    const lista = document.getElementById('lista-de-setores');
    
    // Cria um novo <li> com o HTML exato da lista
    const novoItem = document.createElement('li');
    novoItem.className = 'lista-item';
    novoItem.dataset.setorId = id; // Guarda o ID no 'data-setor-id'
    
    novoItem.innerHTML = `
        <span class="setor-nome">${nome}</span>
        <div class="setor-acoes">
            <a href="#" class="button-excluir js-btn-excluir">Excluir</a>
        </div>
    `;
    
    lista.appendChild(novoItem);
}