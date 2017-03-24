<?php
    session_start();
    $user = $_SESSION["user"];

    $cpf = $user["CPF"];

    $erro = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($_POST["nome"])){
            $erro_nome = "é obrigatório.";
            $erro = true;
        }else{
            $nome = $_POST["nome"];
        }

        if($_POST["tipo"] == 1){
            $tab = "cargo";
        }
        if($_POST["tipo"] == 2){
            $tab = "filial";
        }
        if($_POST["tipo"] == 3){
            $tab = "departamento";
        }

        if(!$erro){
            require 'db_credentials.php';
            $conn = mysqli_connect($servername, $username, $password);
            $sql = "USE $dbname";
            mysqli_query($conn, $sql);

            $sql = "INSERT INTO $tab(nome) VALUES('$nome')";
            mysqli_query($conn, "SET NAMES 'utf8'");
            mysqli_query($conn, $sql);
            mysqli_close($conn);
        }
    }
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Adicionar cargo/filial/departamento</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

        <style media="screen">
            .btn1, .btn2 {
                background: #008a8a;
                text-shadow: 1px 1px 3px #005858;
                font-family: Arial;
                color: #ffffff;
                border: solid #005758 1px;
                text-decoration: none;
                border-radius: 0;
            }

            .btn1 {
                position: relative;
                top: 20%;
                float: right;
                margin-right: 25%;
            }

            .btn2 {
                padding: 1px 10px;
                margin: 1.5% 0;
            }

            a {
                color: #00C4C4;
            }

            a:hover {
                color: #00C4C4;
            }

            .content {
                padding: 15% 25%;
            }

            .form-group {
                padding: 0;
                margin-right: 8.25%;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="header">
                <div class="logo">
                    <img src="logo.png" alt="" />
                </div>

                <script src="http://www.w3schools.com/lib/w3data.js"></script>
                <div w3-include-html="buttonsAdministrador.html"></div>
            </div>

            <div class="content">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" name="calc" onsubmit="return validaFormulario();">
                    <div class="form-group col-xs-3">
                        <select style="color:black;" class="form-control input-sm" name="tipo">
                            <option value="1">Cargo</option>
                            <option value="2">Filial</option>
                            <option value="3">Departamento</option>
                        </select>
                    </div>

                    <div class="form-group col-md-12 <?php if(!empty($erro_nome)){echo "has-error";}?>">
                        <label for="inputNome" class="control-label" id="lnome">Nome
                            <?php if (!empty($erro_nome)){echo $erro_nome;} ?>
                        </label>
                        <input maxlength="9" class="form-control input-sm" type="text" placeholder="Nome" name="nome" id="nome">
                        <button class="btn btn2 btn3 col-md-12" type="submit" name="button">Adicionar</button>
                    </div>

                </form>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                UNLOCK.inc Todos os direitos reservados.
            </div>
        </footer>
        <script>
            w3IncludeHTML();
        </script>
        <script>
        function validaFormulario(){
            var nome = document.forms["calc"]["nome"].value;
            if(nome == null || nome == ""){
                document.getElementById('lnome').style.color = "red";
                document.getElementById('lnome').innerHTML = 'Nome é obrigatório';
                return false;
            }else{
                document.getElementById('lnome').style.color = "white";
                document.getElementById('lnome').innerHTML = 'Nome';
            }
            return true;
        }
        </script>
    </body>
</html>
