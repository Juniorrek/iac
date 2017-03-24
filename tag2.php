<?php
    function validaCPF($cpff) {
        if($cpff == "") return true;
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
    function verifica_campo($texto){
      $texto = trim($texto);
      $texto = stripslashes($texto);
      $texto = htmlspecialchars($texto);
      return $texto;
    }
    session_start();
    $user = $_SESSION["user"];

    $cpf = $user["CPF"];

    require 'db_credentials.php';
    $conn = mysqli_connect($servername, $username, $password);
    $sql = "USE $dbname";
    mysqli_query($conn, $sql);

    if($_SESSION["tipoe"] == 1){
        $tag = $_SESSION["editag"];
        $nome = $_SESSION["editnome"];
        $cpfe = $_SESSION["edit"];
    }else $tag = $_SESSION["edit"];


    $sql = "SELECT * FROM tag WHERE tag.tag = '$tag';";
    $result = mysqli_query($conn, $sql);
    $tage = mysqli_fetch_assoc($result);

    if($_SESSION["tipoe"] == 2) $cpfe = $tage["CPF"];

    $_SESSION["editag"] = $tag;
    $_SESSION["editnome"] = $cpfe;

    $partir = $tage["horaMin"];
    $ate = $tage["horaMax"];
    $dias = $tage["dias"];

    $erro = false;
    if(isset($_POST["salvar"])){
        if($_POST["cpf"] == ""){
            $cpfe = verifica_campo($_POST["cpf"]);
            $_SESSION["editnome"] = $cpfe;
        }else{
            if(!validaCPF($_POST["cpf"])){
                $erro = true;
                $erro_cpf = "invalido.";
            }else{
                $cpft = $_POST["cpf"];
                $sql = "SELECT tag.CPF, tag.master FROM tag WHERE CPF='$cpft' AND tag.master=1";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result)>0) {
                    $erro = true;
                    $erro_cpf = "já possui tag master.";
                }else{
                    $sql = "SELECT adm FROM Usuario WHERE CPF='$cpft'";
                    $result = mysqli_query($conn, $sql);
                    $tagu2 = mysqli_fetch_array($result);
                    $adm = $tagu2["adm"];

                    if($adm){
                        $cpfe = verifica_campo($_POST["cpf"]);
                        $_SESSION["editnome"] = $cpfe;
                    }else{
                        $erro = true;
                        $erro_cpf = "não é administrador.";
                    }
                }
            }
        }

        if(!$erro){
            $sql = "UPDATE tag SET CPF = '$cpfe' WHERE tag.tag = '$tag';";
            mysqli_query( $conn, $sql);
        }
    }

    if(isset($_POST["excluir"])){
        $sql = "DELETE FROM tag WHERE tag.CPF = '$cpfe' AND tag.tag='$tag';";
        mysqli_query($conn, $sql);
        header("Location: pesquisas.php");
    }

    mysqli_close($conn);
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>TAG MASTER</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
        <script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js"/></script>
        <script type="text/javascript">$(document).ready(function(){	$("#cpf").mask("999.999.999-99");});</script>

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
                padding: 5% 20%;
            }

            .form-group {
                padding: 0;
                margin-right: 8.25%;
            }

            input {
                color: black;
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
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group col-xs-2">
                        <label>TAG</label>
                        <input disabled class="form-control input-sm" type="text" value="<?php echo $tag; ?>">
                    </div>

                    <div style="margin-right:7px;" class="form-group col-xs-4 <?php if(!empty($erro_cpf)){echo "has-error";}?>">
                        <label for="inputCpf" class="control-label" id="lcpf">CPF
                            <?php if (!empty($erro_cpf)){echo $erro_cpf;} ?>
                        </label>
                        <input id="cpf" name="cpf" class="form-control input-sm" type="text" value="<?php echo $cpfe; ?>">
                    </div>

                    <br>
                    <input style="padding:2% 2.4%;margin-bottom:3%;" class="btn btn1" type="submit" name="salvar" value="Salvar alterações"></input>
                    <input style="padding:2% 2.4%;margin-bottom:3%;" class="btn btn1" type="submit" name="excluir" value="Excluir TAG"></input>

                    <button disabled onclick="window.location.href='tag2.php'" style="padding-left:16%; padding-right: 16%;margin-bottom:5%;" class="btn btn1" type="button" name="button">TAG MASTER</button>
                </div>
        </div>
        </form>

        <footer class="footer">
            <div class="container">
                UNLOCK.inc Todos os direitos reservados.
            </div>
        </footer>
        <script>
            w3IncludeHTML();
        </script>
    </body>
</html>
