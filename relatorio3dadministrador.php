<?php
    session_start();
    $user = $_SESSION["user"];

    $cpf = $user["CPF"];

    if(isset($_POST["departamento"])){
        $departamento = $_POST["departamento"];
    }else{
        $departamento = 1;
    }
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Relatório 3 administrador</title>
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

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group col-xs-3 <?php if(!empty($erro_departamento)){echo "has-error";}?>">
                        <label for="selectDepartamento" class="control-label" id="ldepartamento">Departamento
                            <?php if (!empty($erro_departamento)){echo $erro_departamento;} ?>
                        </label>
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
                            $query = mysqli_query($conn, "SELECT idDep, nome FROM Departamento");
                            mysqli_close($conn);
                        ?>
                        <select style="color:black;" class="form-control input-sm" name="departamento" id="departamento">
                            <?php while($dep = mysqli_fetch_array($query)) { ?>
                                <option value="<?php echo $dep['idDep'] ?>"><?php echo $dep['nome'] ?></option>
                            <?php } ?>
                        </select>
                        <script type="text/javascript">
                          document.getElementById('departamento').value = "<?php echo $_POST['departamento'];?>";
                        </script>
                    </div>

                    <br><br><br>
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
                    $query = mysqli_query($conn, "SELECT usuario.nome as nome, cargo.nome as cargo, departamento.nome as departamento, filial.nome as filial FROM usuario, cargo, departamento, filial
                                                  WHERE usuario.cargo=cargo.idCargo AND usuario.departamento=departamento.idDep AND usuario.filial=filial.idFilial AND usuario.departamento='$departamento' AND usuario.ativada=1");
                    mysqli_close($conn);
                ?>
                <div id="print">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Cargo</th>
                                <th>Departamento</th>
                                <th>Filial</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dep = mysqli_fetch_array($query)) { ?>
                                <tr>
                                    <td><?php echo $dep["nome"] ?></td>
                                    <td><?php echo $dep["cargo"] ?></td>
                                    <td><?php echo $dep["departamento"] ?></td>
                                    <td><?php echo $dep["filial"] ?></td>
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
