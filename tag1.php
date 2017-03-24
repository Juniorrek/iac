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
        if(!validaCPF($_POST["cpf"])){
            $erro = true;
            $erro_cpf = "invalido.";
        }else{
            $cpfe = verifica_campo($_POST["cpf"]);
            $_SESSION["editnome"] = $cpfe;
        }
        $partir = $_POST["partir"];
        if(empty($partir)) $partir = "00:00";
        if($partir[0]>2) $partir = "00:00";
        if($partir[1]>3) $partir = "00:00";
        if($partir[3]>5) $partir = "00:00";
        $ate = $_POST["ate"];
        if(empty($ate)) $ate = "23:00";
        if($ate[0]>2) $ate = "23:00";
        if($ate[1]>3) $ate = "23:00";
        if($ate[3]>5) $ate = "23:00";
        if(isset($_POST["domingo"])) $dias[0] = "1";
        else $dias[0] = "0";
        if(isset($_POST["segunda"])) $dias[1] = "1";
        else $dias[1] = "0";
        if(isset($_POST["terca"])) $dias[2] = "1";
        else $dias[2] = "0";
        if(isset($_POST["quarta"])) $dias[3] = "1";
        else $dias[3] = "0";
        if(isset($_POST["quinta"])) $dias[4] = "1";
        else $dias[4] = "0";
        if(isset($_POST["sexta"])) $dias[5] = "1";
        else $dias[5] = "0";
        if(isset($_POST["sabado"])) $dias[6] = "1";
        else $dias[6] = "0";

        if(!$erro){
            $sql = "UPDATE tag SET horaMin = '$partir', horaMax = '$ate', dias = '$dias', CPF = '$cpfe' WHERE tag.tag = '$tag';";
            mysqli_query( $conn, $sql);
        }
    }

    if(isset($_POST["excluir"])){
        $sql = "DELETE FROM acesso WHERE acesso.TAG='$tag';";
        mysqli_query($conn, $sql);
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
        <title>TAG 1</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
        <script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js"/></script>
        <script type="text/javascript">$(document).ready(function(){	$("#horamin").mask("99:99");});</script>
        <script type="text/javascript">$(document).ready(function(){	$("#horamax").mask("99:99");});</script>
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

                    <button onclick="window.location.href='tag1.php'" style="padding-left:16%; padding-right: 16%;margin-bottom:5%;" class="btn btn1" type="button" name="button">Horários permitidos</button>
                    <button onclick="window.location.href='tag3.php'" style="padding-left:16%; padding-right: 16%;margin-bottom:5%;" class="btn btn1" type="button" name="button">Histórico de acessos</button>

                    <br>
                    <input style="margin-left:1%;" type="checkbox" name="domingo" <?php echo ($dias[0] == "1" ? "checked" : ""); ?>>Domingo
                    <input style="margin-left:5%;" type="checkbox" name="segunda" <?php echo ($dias[1] == "1" ? "checked" : ""); ?>>Segunda
                    <input style="margin-left:5%;" type="checkbox" name="terca" <?php echo ($dias[2] == "1" ? "checked" : ""); ?>>Terça
                    <input style="margin-left:8%;" type="checkbox" name="quarta" <?php echo ($dias[3] == "1" ? "checked" : ""); ?>>Quarta
                    <input style="margin-left:7%;" type="checkbox" name="quinta" <?php echo ($dias[4] == "1" ? "checked" : ""); ?>>Quinta
                    <input style="margin-left:7%;" type="checkbox" name="sexta" <?php echo ($dias[5] == "1" ? "checked" : ""); ?>>Sexta
                    <input style="margin-left:7%;" type="checkbox" name="sabado" <?php echo ($dias[6] == "1" ? "checked" : ""); ?>>Sábado
                    <div class="form-group col-md-5">
                        <label for="inputNome" class="control-label">A partir de</label>
                        <input id="horamin" class="form-control input-sm" type="text" placeholder="00:00" name="partir" value="<?php echo $partir; ?>">
                    </div>

                    <div class="form-group col-md-5">
                        <label for="inputNome" class="control-label">Até</label>
                        <input id="horamax" class="form-control input-sm" type="text" placeholder="23:00" name="ate" value="<?php echo $ate; ?>">
                    </div>
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
