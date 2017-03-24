<?php
    session_start();
    $user = $_SESSION["user"];

    $cpf = $user["CPF"];

    if(isset($_POST["data"])){
        if(!empty($_POST["data"])){
             $data = $_POST["data"];
         }else{
             $data = date("d-m-Y");
         }
    }else{
        $data = date("d-m-Y");
    }
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Relatório 1 administrador</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
        <script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js"/></script>
        <script type="text/javascript">$(document).ready(function(){	$("#data").mask("99/99/9999");});</script>

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

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="formularioCadastro" onsubmit="return validaFormulario()">
                    <div class="form-group col-xs-6">
                        <label id="ldata">Data</label>
                        <input class="form-control input-sm" type="text" id="data" name="data">
                        <script>
                            document.getElementById('data').value = "<?php echo $data;?>";
                        </script>
                    </div>

                    <br>
                    <input class="btn btn3" type="submit" name="button" value="Procurar"></input>
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
                    $datab = $data[6].$data[7].$data[8].$data[9]."/".$data[3].$data[4]."/".$data[0].$data[1];
                    $query = mysqli_query($conn, "SELECT usuario.nome as nome, acesso.tag as tag, acesso.data as data, acesso.horario as horario FROM usuario, acesso, tag
                                                  WHERE usuario.cpf=tag.cpf AND tag.tag=acesso.tag AND acesso.data='$datab'");
                    mysqli_close($conn);
                ?>
                <div id="print">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>TAG</th>
                                <th>Data</th>
                                <th>Horário</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dep = mysqli_fetch_array($query)) { ?>
                                <tr>
                                    <td><?php echo $dep["nome"] ?></td>
                                    <td><?php echo $dep["tag"] ?></td>
                                    <td><?php echo $dep["data"][8].$dep["data"][9]."/".$dep["data"][5].$dep["data"][6]."/".$dep["data"][0].$dep["data"][1].$dep["data"][2].
                                    $dep["data"][3] ?></td>
                                    <td><?php echo $dep["horario"] ?></td>
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
        <script src="js/relatorio1administrador.js"></script>
    </body>
</html>
