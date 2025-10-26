
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="shortcut icon" href="../../public/img/favicon-tres-glorias.ico" type="image/x-icon">

    <!--Fontes-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


    <!--Estilos-->
    <link rel="stylesheet" href="../../public/css/login.css">

    <link rel="stylesheet" href="../../public/css/preset.css">

</head>
<body>
    <div class="container">
        <main>
            <div id="login">
                    
                <div class="apresentacao">
                    <img src="../../public/img/logo-tres-glorias-semfundo.png" alt="Logo Tres Glorias" width="
                    210px">
            
                    <div id="titulo">
                        <h1>LOGIN</h1>
                        <h2>Bem-vindo de volta!</h2>
                    </div>
                </div>
                
                <div class="preenchimento">
                    <p>
                        <label for="inUser">Nome de usuário: </label>
                        <input type="text" id="inUser">
                    </p>

                    <p id="preenchimentoSenha">
                        <label for="inPassword">Senha: </label>
                        <input type="password" id="inPassword">
                        <img id="btOlhoFechado" class="mostrar" src="../../public/img/closed-eyes.png" alt="icone olho fechado" width="20px">
                        <img id="btOlhoAberto" class="naoMostrar" src="../../public/img/opened-eyes.png" alt="icone olho aberto" width="20px">
                    </p>

                    <button id="btLogin" class="buttonVerde" style="margin-top: 20px;">Entrar</button>
                </div>

            </div>

        </main>    

        <footer>
            <div class="titulo-footer">
                <div class="container-img"><img src="../../public/img/cadeado.png" alt="Cadeado icone" width="25px"></div>
                <h2>Ambiente seguro</h2>
            </div>

            <div class="info-footer">
                <p>Copyright© Três Glórias 2025 - Super Mercado Três Glórias S/A - Endereço: Av. Eng. Abdias de Carvalho, 1678 - Madalena - Recife - PE - CEP: 50720-225</p>
            </div>

            
        </footer>
    </div>

    <script src="../../public/js/login.js"></script>
</body>
</html>