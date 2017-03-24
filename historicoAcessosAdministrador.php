<?php
    session_start();
    $user = $_SESSION["user"];

    $cpf = $user["CPF"];
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Histórico de acessos cliente</title>
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

            <div class="content" id="print">
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
                    $query = mysqli_query($conn, "SELECT acesso.data, acesso.horario, acesso.TAG FROM tag, usuario, acesso WHERE acesso.tag=tag.tag AND tag.cpf=usuario.cpf AND usuario.cpf='$cpf'");
                    mysqli_close($conn);
                ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>TAG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($dep = mysqli_fetch_array($query)) { ?>
                            <tr>
                                <td><?php echo $dep["data"][8].$dep["data"][9]."/".$dep["data"][5].$dep["data"][6]."/".$dep["data"][0].$dep["data"][1].$dep["data"][2].
                                $dep["data"][3] ?></td>
                                <td><?php echo $dep["horario"] ?></td>
                                <td><?php echo $dep["TAG"] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
