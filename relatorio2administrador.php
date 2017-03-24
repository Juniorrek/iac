<?php
    session_start();
    $user = $_SESSION["user"];

    $cpf = $user["CPF"];

 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Relatório 2 administrador</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

        <style media="screen">
            .btn1, .btn2, .btn3 {
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
                top: 30%;
                float: right;
                margin-right: 24%;
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
                <div class="form-group col-xs-4">
                    <label>Tipo</label><br>
                    <button onclick="window.location.href='relatorio1administrador.php'" class="btn btn2" type="button" name="button">Acesso por data</button><br>
                    <button onclick="window.location.href='relatorio2administrador.php'" class="btn btn2" type="button" name="button">Total usuários</button><br>
                    <button onclick="window.location.href='relatorio3cadministrador.php'" class="btn btn2" type="button" name="button">Por cargo/</button>
                    <button onclick="window.location.href='relatorio3dadministrador.php'" class="btn btn2" type="button" name="button">Departamento/</button>
                    <button onclick="window.location.href='relatorio3fadministrador.php'" class="btn btn2" type="button" name="button">Filial</button>
                    <button onclick="window.location.href='relatorio4administrador.php'" class="btn btn2" type="button" name="button">Faltas hoje</button>
                </div>

                <br>

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
                    $query = mysqli_query($conn, "SELECT usuario.nome as unome, usuario.cpf as cpf, departamento.nome as dnome, cargo.nome as cnome, filial.nome as fnome FROM usuario, departamento, cargo, filial
                                                  WHERE usuario.departamento=departamento.idDep AND usuario.cargo=cargo.idCargo AND usuario.filial=filial.idFilial AND usuario.ativada=1");
                    mysqli_close($conn);
                ?>
                <div id="print">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Departamento</th>
                                <th>Cargo</th>
                                <th>Filial</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dep = mysqli_fetch_array($query)) { ?>
                                <tr>
                                    <td><?php echo $dep["unome"] ?></td>
                                    <td><?php echo $dep["cpf"] ?></td>
                                    <td><?php echo $dep["dnome"] ?></td>
                                    <td><?php echo $dep["cnome"] ?></td>
                                    <td><?php echo $dep["fnome"] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <button class="btn btn1" type="button" name="button" onclick="cont();">Imprimir</button>
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
            function cont(){
               var conteudo = document.getElementById('print').innerHTML;
               tela_impressao = window.open('about:blank');
               tela_impressao.document.write(conteudo);
               tela_impressao.window.print();
               tela_impressao.window.close();
            }
        </script>
    </body>
</html>
