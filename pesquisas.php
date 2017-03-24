<?php
    session_start();
    $user = $_SESSION["user"];

    $cpf = $user["CPF"];

    $nome = "";
    $erro = false;
    $por = 1;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST["editar"])){
            if($_SESSION["por"] == 1 || $_SESSION["por"] == 2){
                $_SESSION["edit"] = $_POST["editar"];
                $_SESSION["tipoe"] = 1;
                header("Location: editarDadosCadastrais.php");
            }else{
                require 'db_credentials.php';
                $conn = mysqli_connect($servername, $username, $password);
                $sql = "USE $dbname";
                mysqli_query($conn, $sql);

                $tagee = $_POST["editar"];
                $sql = "SELECT tag.master FROM tag WHERE tag='$tagee'";
                $result = mysqli_query($conn, $sql);
                $tagu2 = mysqli_fetch_array($result);
                $master = $tagu2["master"];

                $_SESSION["edit"] = $_POST["editar"];
                $_SESSION["tipoe"] = 2;
                if($master) header("Location: tag2.php");
                else header("Location: tag1.php");

                mysqli_close($conn);
            }
        }else{
            if(empty($_POST["nome"])){
              $erro_nome = "é obrigatório.";
              $erro = true;
            }

            if(!$erro){
                if($_POST["pesquisar"] == 1){
                    $por = 1;
                    $_SESSION["por"] = 1;
                }
                if($_POST["pesquisar"] == 2){
                    $por = 2;
                    $_SESSION["por"] = 2;
                }
                if($_POST["pesquisar"] == 3){
                    $por = 3;
                    $_SESSION["por"] = 3;
                }

                $nome = $_POST["nome"];
            }
        }
    }
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Pesquisas</title>
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
                padding: 5% 25%;
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
                    <div class="form-group col-xs-2">
                        <label>Pesquisar por</label>
                        <select class="form-control" name="pesquisar" id="pesquisar">
                            <option value="1">CPF</option>
                            <option value="2">Nome</option>
                            <option value="3">TAG</option>
                        </select>
                        <script type="text/javascript">
                          document.getElementById('pesquisar').value = "<?php echo $_POST['pesquisar'];?>";
                        </script>
                    </div>

                    <div class="form-group col-xs-7 <?php if(!empty($erro_nome)){echo "has-error";}?>">
                        <label for="inputNome" class="control-label" id="lnome">:
                            <?php if (!empty($erro_nome)){echo $erro_nome;} ?>
                        </label>
                        <input class="form-control input-sm" type="text" name="nome">
                    </div>

                    <br>
                    <input class="btn btn1" type="submit" name="button" value="Pesquisar"></input>
                </form>

                <?php
                    require 'db_credentials.php';
                    $conn = mysqli_connect($servername, $username, $password);
                    if (!$conn) {
                       die("Connection failed: " . mysqli_connect_error());
                    }

                    $sql = "USE $dbname";
                    if (!mysqli_query($conn, $sql)) {
                        die("Error acess database: " . mysqli_error($conn));
                    }
                    mysqli_query($conn, "SET NAMES 'utf8'");
                    if($por == 1){
                        $query = mysqli_query($conn, "SELECT usuario.nome as nome, usuario.cpf as cpf FROM usuario
                                                      WHERE usuario.cpf='$nome'");
                    }
                    if($por == 2){
                        $query = mysqli_query($conn, "SELECT usuario.nome as nome, usuario.cpf as cpf FROM usuario
                                                      WHERE usuario.nome='$nome';");
                    }
                    if($por == 3){
                        $query = mysqli_query($conn, "SELECT tag.tag as tag, tag.master as master FROM tag
                                                      WHERE tag.tag='$nome';");
                    }
                    mysqli_close($conn);
                ?>
                <?php if($por == 1 || $por == 2): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($dep = mysqli_fetch_array($query)) { ?>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <tr>
                                    <td><?php echo $dep["nome"] ?></td>
                                    <td><?php echo $dep["cpf"] ?></td>
                                    <td><input class="btn btn1" type="submit" name="editar" value="<?php echo $dep["cpf"] ?>"></input></td>
                                </tr>
                            </form>
                        <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <?php if($por == 3): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>TAG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($dep = mysqli_fetch_array($query)) { ?>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <tr>
                                    <td><?php if($dep["master"]==1) echo $dep["tag"]."M";else echo $dep["tag"] ?></td>
                                    <td><input class="btn btn1" type="submit" name="editar" value="<?php echo $dep["tag"] ?>"></input></td>
                                </tr>
                            </form>
                        <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>
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
