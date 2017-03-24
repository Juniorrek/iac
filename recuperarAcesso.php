<?php
    include 'crypt.php';
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

    $cpf = $email = "";
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

            $sql = "SELECT CPF, email, senha FROM Usuario WHERE CPF='$cpf'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result)>0) {
                $row = mysqli_fetch_assoc($result);
                $to = $row["email"];
                $senha = $row["senha"];
                $senha = decryptIt( $senha );

                require 'phpmailer/class.phpmailer.php';
                require 'phpmailer/class.smtp.php';

                $mail = new PHPMailer();
                $mail->setLanguage('pt');

                $host = 'smtp.live.com';
                $username = 'juniorrek15@hotmail.com';
                $password = '1904455';
                $port = 587;
                $secure = 'tls';

                $from = $username;
                $fromName = 'SUA SENHA';

                $mail->isSMTP();
                $mail->Host = $host;
                $mail->SMTPAuth = true;
                $mail->Username = $username;
                $mail->Password = $password;
                $mail->Port = $port;
                $mail->SMTPSecure = $secure;

                $mail->From = $from;
                $mail->FromName = $fromName;
                $mail->addReplyTo($from, $fromName);

                $mail->addAddress($to, 'SENHA');

                $mail->isHTML(true);
                $mail->Charset = 'utf-8';
                $mail->WordWrap = 70;

                $mail->Subject = 'SUA SENHA';
                $mail->Body = 'Sua senha :'.$senha;

                $send = $mail->Send();
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
        <title>Recuperar acesso</title>
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
                left: 62%;
            }

            .btn1 {
                top: 33%;
                left: 74%;
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

            .content {
                padding: 15% 35%;
            }

            .form-group {
                padding: 0;
                margin-right: 8.25%;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <!-- <div class="header">
                <div class="logo">
                    <img src="logo.png" alt="" />
                </div>

                <button onclick="window.location.href='telaInicialAcesso.php'" class="btn btn1" type="button" name="button">Voltar</button>
            </div> -->

            <div class="content">
                Digite seu CPF, caso ele conste em nosso banco de dados um email com as proximas instruções
                serão enviadas para o seu respectivo email.

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group <?php if(!empty($erro_cpf)){echo "has-error";}?>">
                        <label for="inputCpf" class="control-label">CPF <?php if (!empty($erro_cpf)){echo $erro_cpf;} ?></label>
                        <input id="cpf" class="form-control input-sm" type="text" name="cpf" style="color: black;" placeholder="CPF" value="<?php echo $cpf; ?>">
                    </div>

                    <button class="btn btn2" type="submit" name="button">Recuperar</button>
                </form>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                UNLOCK.inc Todos os direitos reservados.
            </div>
        </footer>
    </body>
</html>
