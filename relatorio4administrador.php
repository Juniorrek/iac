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
        <title>Relatório 4 administrador</title>
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
                    $datab = $data[6].$data[7].$data[8].$data[9]."-".$data[3].$data[4]."-".$data[0].$data[1];
                    $query = mysqli_query($conn, "SELECT usuario.nome, usuario.CPF, cargo.nome as cargo, departamento.nome as departamento, filial.nome as filial, tag.dias as dias, tag.tag as tag
                                                  FROM usuario, cargo, departamento, filial, tag
                                                  WHERE usuario.cargo=cargo.idCargo AND usuario.departamento=departamento.idDep AND usuario.filial=filial.idFilial AND tag.CPF=usuario.CPF");
                ?>
                <div id="print">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Cargo</th>
                                <th>Departamento</th>
                                <th>Filial</th>
                                <th><?php echo $data ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dep = mysqli_fetch_array($query)) {
                                    $cpff = $dep["CPF"];
                                    $semana = $dep["dias"];
                                    $tagg = $dep["tag"];
                                    $sql = "SELECT * FROM acesso WHERE acesso.TAG='$tagg'";
                                    $result = mysqli_query($conn, $sql);
                                    $dian = date('w', time());
                                    if(strpos($semana,"1") == $dian && mysqli_num_rows($result) == 0){

                            ?>
                                <tr>
                                    <td><?php echo $dep["nome"] ?></td>
                                    <td><?php echo $dep["CPF"] ?></td>
                                    <td><?php echo $dep["cargo"] ?></td>
                                    <td><?php echo $dep["departamento"] ?></td>
                                    <td><?php echo $dep["filial"] ?></td>
                                </tr>
                            <?php }}mysqli_close($conn);?>
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
