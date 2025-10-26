//Funcionalidade do olho de mostrar senha

//Mostrar ou ocultar senha 
function MudarOlho(){

    let inputSenha = document.getElementById("inPassword");

    if(inputSenha.type == "password"){
        btOlhoFechado.className = "naoMostrar";
        btOlhoAberto.className = "mostrar";
        inputSenha.type = "text";
    }
    else if(inputSenha.type == "text"){
        btOlhoAberto.className = "naoMostrar";
        btOlhoFechado.className = "mostrar";
        inputSenha.type = "password";
    }
}

let btOlhoAberto = document.getElementById("btOlhoAberto");

let btOlhoFechado = document.getElementById("btOlhoFechado");

btOlhoAberto.addEventListener("click", MudarOlho);
btOlhoFechado.addEventListener("click", MudarOlho);