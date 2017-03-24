<?php
    include 'crypt.php';
    session_start();
    session_unset();

    session_destroy();
    function verifica_campo($texto){
        $texto = trim($texto);
        $texto = stripslashes($texto);
        $texto = htmlspecialchars($texto);
        return $texto;
    }

    function validaCPF($cpff) {
        // Elimina possivel mascara
        $cpff = preg_replace('/[^0-9]/', '', $cpff);
        $cpff = str_pad($cpff, 11, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpff) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpff == '00000000000' ||
            $cpff == '11111111111' ||
            $cpff == '22222222222' ||
            $cpff == '33333333333' ||
            $cpff == '44444444444' ||
            $cpff == '55555555555' ||
            $cpff == '66666666666' ||
            $cpff == '77777777777' ||
            $cpff == '88888888888' ||
            $cpff == '99999999999') {
            return false;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpff{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpff{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    $cpf = $senha = "";
    $erro = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($_POST["cpf"])){
            $erro_cpf = "é obrigatório.";
            $erro = true;
        }else{
            $cpf = verifica_campo($_POST["cpf"]);
            if(!validaCPF($cpf)){
                $erro_cpf = "invalido.";
                $erro = true;
            }
        }

        if(empty($_POST["senha"])){
            $erro_senha = "é obrigatória.";
            $erro = true;
        }else{
            $senha = verifica_campo($_POST["senha"]);
        }

        if(!$erro){
            require 'db_credentials.php';
            $conn = mysqli_connect($servername, $username, $password);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "USE $dbname";
            if (!mysqli_query($conn, $sql)) {
                die("Error acess database: " . mysqli_error($conn));
            }

            //$senha = md5($senha);
            $senha = encryptIt( $senha );
            mysqli_query($conn, "SET NAMES 'utf8'");
            $sql = "SELECT * FROM Usuario WHERE CPF='$cpf' AND Senha='$senha'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result)>0) {
                $user = mysqli_fetch_assoc($result);
                if($user["ativada"] == 1){
                    session_start();

                    $_SESSION["user"] = $user;

                    if($user["adm"] == 0){
                        header("Location: dadosCadastraisCliente.php");
                    }else{
                        header("Location: dadosCadastraisAdministrador.php");
                    }

                }else{
                    $erro_cpf = ", conta bloqueada.";
                }
            } else {
                $erro_cpf = "inexistente.";
            }
            mysqli_close($conn);
        }
    }
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Tela inicial de acesso</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
        <script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js"/></script>
        <script type="text/javascript">$(document).ready(function(){	$("#cpf").mask("999.999.999-99");});</script>

        <style media="screen">
            .inputCpf, .inputSenha, .btn {
                display: inline-block;
                position: absolute;
            }

            .inputCpf {
                top: 5%;
                left: 40%;
            }

            .inputSenha {
                top: 5%;
                left: 53%;
            }

            .btn1 {
                top: 33%;
                left: 65%;
            }

            .btn1, .btn2 {
                background: #008a8a;
                text-shadow: 1px 1px 3px #005858;
                font-family: Arial;
                color: #ffffff;
                border: solid #005758 1px;
                text-decoration: none;
                border-radius: 0;
                padding: 1px 10px;
            }

            a {
                color: #00C4C4;
            }

            a:hover {
                color: #00C4C4;
            }

            .esq, .dir {
                position: absolute;
                top: 0;
                bottom: 0;
                display: inline-block;
                width: 50%;
            }

            .esq {
                left: 0;
                text-align: justify;
                padding: 15%;
            }

            .dir {
                padding-top: 15%;
                left: 50%;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="header">
                <div class="logo">
                    <img src="logo.png" alt="" />
                </div>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="inputCpf <?php if(!empty($erro_cpf)){echo "has-error";}?>">
                        <label for="inputCpf" class="control-label">CPF <?php if (!empty($erro_cpf)){echo $erro_cpf;} ?></label>
                        <input type="text" name="cpf" style="color: black;" placeholder="CPF" value="<?php echo $cpf; ?>" id="cpf"><br>
                    </div>

                    <div class="inputSenha <?php if(!empty($erro_senha)){echo "has-error";}?>">
                        <label for="inputSenha" class="control-label">Senha <?php if (!empty($erro_senha)){echo $erro_senha;} ?></label>
                        <input type="password" name="senha" placeholder="Senha" style="color: black;"><br>
                        <a href="recuperarAcesso.php">Esqueci minha senha</a>
                    </div>

                    <button class="btn btn1" type="submit" name="button">Login</button>
                </form>
            </div>

            <div class="content">
                <div class="esq">
                    UNLOCK é um sistema gerenciador de acesso eletrônico projetado totalmente no ambiente WEB, permitindo um controle de acesso
                    mais facilitado, tal controle possui parâmetros como horário e dia do acesso. O sistema também disponibiliza uma série de relatórios
                    para os administradores analisarem melhor o fluxo de acessos.
                </div>

                <div class="dir">
                    Ainda nao possui uma conta?<br> Solicite uma para os administradores do sistema.<br>

                    <button onclick="window.location.href='cadastroUsuario.php'" class="btn btn2" type="button" name="button">Cadastro</button>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                UNLOCK.inc Todos os direitos reservados.
            </div>
        </footer>
    </body>
</html>
