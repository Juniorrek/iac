<?php
    session_start();
    $user = $_SESSION["user"];

    $cpf = $user["CPF"];

    if(isset($_POST["aprovar"])){
        $cpfa = $_POST["aprovar"];
        require 'db_credentials.php';
        $conn = mysqli_connect($servername, $username, $password);
        $sql = "USE $dbname";
        mysqli_query($conn, $sql);

        $sql = "UPDATE usuario SET ativada = '1' WHERE usuario.CPF = '$cpfa';";
        mysqli_query($conn, $sql);
    }
    if(isset($_POST["reprovar"])){
        require 'db_credentials.php';
        $conn = mysqli_connect($servername, $username, $password);
        $sql = "USE $dbname";
        mysqli_query($conn, $sql);
        $cpfa = $_POST["reprovar"];

        $sql = "DELETE FROM usuario WHERE usuario.CPF = '$cpfa';";
        mysqli_query($conn, $sql);
    }

 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Solicitações</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

        <style media="screen">
            * {
                color: white;
            }
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
                    $query = mysqli_query($conn, "SELECT usuario.nome as unome, departamento.nome as dnome, cargo.nome as cnome, filial.nome as fnome, usuario.CPF FROM usuario, departamento, cargo, filial
                                                  WHERE departamento.idDep=usuario.departamento AND cargo.idCargo=usuario.cargo AND filial.idFilial=usuario.filial AND usuario.ativada=0");
                    mysqli_close($conn);
                ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Departamento</th>
                            <th>Cargo</th>
                            <th>Filial</th>
                            <th>CPF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($dep = mysqli_fetch_array($query)) { ?>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <tr>
                                    <td><?php echo $dep["unome"] ?></td>
                                    <td><?php echo $dep["dnome"] ?></td>
                                    <td><?php echo $dep["cnome"] ?></td>
                                    <td><?php echo $dep["fnome"] ?></td>
                                    <td><?php echo $dep["CPF"] ?></td>
                                    <td><input class="btn btn1" type="submit" name="aprovar" value="<?php echo $dep["CPF"] ?>"><span class="glyphicon glyphicon-ok"></span></input></td>
                                    <td><input class="btn btn1" type="submit" name="reprovar" value="<?php echo $dep["CPF"] ?>"><span class="glyphicon glyphicon-remove"></span></input></td>
                                </tr>
                            </form>
                        <?php } ?>
                    </tbody>
                </table>
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
    </body>
</html>
