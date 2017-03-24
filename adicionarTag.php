<?php

    $erro = false;
    $dias = "0000000";
    $partir = "00:00";
    $ate = "23:00";
    $cpfa = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require 'db_credentials.php';
        $conn = mysqli_connect($servername, $username, $password);
        $sql = "USE $dbname";
        mysqli_query($conn, $sql);

        if(empty($_POST["exibicao"])){
            $erro_tag = "é obrigatória.";
            $erro = true;
        }else{
            $lenght = strlen($_POST["exibicao"]);
            if($lenght == 9){
                $taga = $_POST["exibicao"];
            }else{
                $erro_tag = "precisa de 9 digitos.";
                $erro = true;
            }
        }

        if(isset($_POST["master"])) $master = true;
        else $master = 0;

        if($master == true){
            $dias = "";
            $partir = "";
            $ate = "";
        }

        if(!$erro){

            $sql = "SELECT tag.tag FROM tag WHERE tag.tag='$taga'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 0) {
                $sql = "INSERT INTO tag(tag, CPF, horaMin, horaMax, dias, master) VALUES('$taga', '$cpfa', '$partir', '$ate', '$dias', '$master')";
                mysqli_query($conn, $sql);
            }else{
                $erro_tag = " ja existe.";
                $erro = true;
            }
        }
        mysqli_close($conn);
    }
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Adicionar TAG</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
        <script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js"/></script>
        <script type="text/javascript">$(document).ready(function(){	$("#exibicao").mask("999999999");});</script>

        <style media="screen">
            .inputCpf, .inputSenha, .btn {
                display: inline-block;
                position: absolute;
            }

            .inputCpf {
                top: 5%;
                left: 50%;
            }

            .inputSenha {
                top: 5%;
                left: 62%;
            }

            .btn1 {
                top: 33%;
                left: 74%;
                padding: 1px 10px;
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

            .btn3 {
                padding: 2% 0;
            }

            a {
                color: #00C4C4;
            }

            a:hover {
                color: #00C4C4;
            }

            .content {
                padding: 10% 35%;
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
                <button style="margin: 1% 70%;" onclick="window.location.href='registrarAcesso.php'" class="btn btn2" type="button" name="button">Voltar</button>
            </div>

            <div class="content">
                <button class="btn2 btn3 col-md-4" type="button" name="button" onclick="Inserir(7)">7</button>
                <button class="btn2 btn3 col-md-4" type="button" name="button" onclick="Inserir(8)">8</button>
                <button class="btn2 btn3 col-md-4" type="button" name="button" onclick="Inserir(9)">9</button><br>
                <button class="btn2 btn3 col-md-4" type="button" name="button" onclick="Inserir(4)">4</button>
                <button class="btn2 btn3 col-md-4" type="button" name="button" onclick="Inserir(5)">5</button>
                <button class="btn2 btn3 col-md-4" type="button" name="button" onclick="Inserir(6)">6</button><br>
                <button class="btn2 btn3 col-md-4" type="button" name="button" onclick="Inserir(1)">1</button>
                <button class="btn2 btn3 col-md-4" type="button" name="button" onclick="Inserir(2)">2</button>
                <button class="btn2 btn3 col-md-4" type="button" name="button" onclick="Inserir(3)">3</button><br>
                <button class="btn2 btn3 col-md-6" type="button" name="button" onclick="Inserir(0)">0</button>
                <button class="btn2 btn3 col-md-6" type="button" name="button" onclick="Limpa()">C</button>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" name="calc" onsubmit="return validaTag()">
                    <div class="form-group col-md-12 <?php if(!empty($erro_tag)){echo "has-error";}?>">
                        <label for="inputTag" class="control-label" id="ltag">TAG
                            <?php if (!empty($erro_tag)){echo $erro_tag;} ?>
                        </label>
                        <input maxlength="9" class="form-control input-sm col-md-5" type="text" placeholder="TAG" id="exibicao" name="exibicao">
                        <input style="margin-left:1%;" type="checkbox" name="master">Master<br>
                        <br><br><button class="btn btn2 btn3 col-md-12" type="submit" name="button">Adicionar</button>
                    </div>

                </form>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                UNLOCK.inc Todos os direitos reservados.
            </div>
        </footer>

        <script type="text/javascript">
            var value = 0;
            function Inserir(numero) {
                if(value < 9){
                    document.calc.exibicao.value += numero;
                    value++;
                }
            }

            function Limpa() {
                value = 0;
            	document.calc.exibicao.value = '';
            }
        </script>
        <script src="js/registrarAcesso.js"></script>
    </body>
</html>
