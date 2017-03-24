<?php
    session_start();
    $user = $_SESSION["user"];

    $cpf = $user["CPF"];

    $cpfe = $_SESSION["edit"];
    require 'db_credentials.php';
    $conn = mysqli_connect($servername, $username, $password);
    $sql = "USE $dbname";
    mysqli_query($conn, $sql);
    $sql = "SELECT * FROM usuario WHERE usuario.CPF='$cpfe'";
    $result = mysqli_query($conn, $sql);
    $usere = mysqli_fetch_assoc($result);

    $nome = $usere["nome"];
    $cpfe = $usere["CPF"];
    $email = $usere["email"];
    $nome = $usere["nome"];
    $telefone = $usere["Telefone"];
    $senha = $usere["Senha"];
    $foto = $usere["foto"];
    if($foto == "imagens/") $foto = "imagens/semfoto.png";
    $nascimento = $usere["nasc"];
    $idEndereco = $usere["Endereco"];

    $sql = "SELECT * FROM endereco, usuario WHERE usuario.endereco=endereco.idEndereco AND usuario.cpf='$cpfe'";
    $result = mysqli_query($conn, $sql);
    $endereco = mysqli_fetch_assoc($result);
    $cep = $endereco["CEP"];
    $estado = $endereco["estado"];
    $cidade = $endereco["cidade"];
    $rua = $endereco["rua"];
    $bairro = $endereco["bairro"];
    $numero = $endereco["numero"];

    $departamento = $usere["departamento"];
    $filial = $usere["filial"];
    $cargo = $usere["cargo"];

    if($usere["adm"] == 0)
        $tipo = "usuario";
    else
        $tipo = "administrador";

    if(isset($_POST["salvar"])){
        $cargo = $_POST["cargo"];
        $departamento = $_POST["departamento"];
        $filial = $_POST["filial"];
        if($_POST["tipo"] == "usuario"){
            $adm = 0;
            $tipo = "usuario";
        }else{
            $adm = true;
            $tipo = "administrador";
        }
        $sql = "UPDATE usuario SET departamento = '$departamento', filial = '$filial', cargo = '$cargo', adm = '$adm' WHERE usuario.CPF = '$cpfe';";
        if (!mysqli_query($conn, $sql)) {
            die("Error acess database: " . mysqli_error($conn));
        }
    }

    if(isset($_POST["excluir"])){
        $sql = "DELETE FROM tag WHERE tag.CPF = '$cpfe';";
        mysqli_query($conn, $sql);
        $sql = "DELETE FROM endereco WHERE endereco.idEndereco = '$idEndereco';";
        mysqli_query($conn, $sql);
        $sql = "DELETE FROM usuario WHERE usuario.CPF = '$cpfe';";
        mysqli_query($conn, $sql);
        header("Location: pesquisas.php");
    }

    if(isset($_POST["editag"])){
        $_SESSION["editnome"] = $nome;
        $_SESSION["editag"] = $_POST["tag"];
        $_SESSION["edit"] = $cpfe;
        $_SESSION["tipoe"] = 1;

        $tagee = $_POST["tag"];
        $sql = "SELECT tag.master FROM tag WHERE tag='$tagee'";
        $result = mysqli_query($conn, $sql);
        $tagu2 = mysqli_fetch_array($result);
        $master = $tagu2["master"];

        if($master) header("Location: tag2.php");
        else header("Location: tag1.php");

    }

    mysqli_close($conn);
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Editar dados cadastrais</title>
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
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <fieldset>
                        <legend>Dados pessoais</legend>

                        <div class="form-group <?php if(!empty($erro_nome)){echo "has-error";}?>">
                            <label for="inputNome" class="control-label">Nome completo
                                <?php if (!empty($erro_nome)){echo $erro_nome;} ?>
                            </label>
                            <input disabled class="form-control input-sm" name="nome" type="text" placeholder="Nome completo" value="<?php echo $nome; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_cpf)){echo "has-error";}?>">
                            <label for="inputCpf" class="control-label">CPF
                                <?php if (!empty($erro_cpf)){echo $erro_cpf;} ?>
                            </label>
                            <input disabled class="form-control input-sm" name="cpf" type="text" placeholder="CPF" value="<?php echo $cpfe; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_email)){echo "has-error";}?>">
                            <label for="inputEmail" class="control-label">Email
                                <?php if (!empty($erro_email)){echo $erro_email;} ?>
                            </label>
                            <input disabled class="form-control input-sm" name="email" type="text" placeholder="Email" value="<?php echo $email; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_email)){echo "has-error";}?>">
                            <label for="inputSenha" class="control-label">Senha
                                <?php if (!empty($erro_senha)){echo $erro_senha;} ?>
                            </label>
                            <input disabled class="form-control input-sm" name="senha" type="password" placeholder="Senha" value="<?php echo $senha; ?>">
                        </div>

                        <div class="form-group col-xs-5">
                            <label>Data de nascimento</label>
                            <input disabled class="form-control input-sm" type="date" name="nascimento" id="nascimento">
                            <script>
                                document.getElementById('nascimento').value = "<?php echo $nascimento;?>";
                            </script>
                        </div>

                        <div class="form-group col-xs-5">
                            <label for="inputTelefone" class="control-label">Telefone</label>
                            <input disabled class="form-control input-sm" type="text" placeholder="Telefone" name="telefone" value="<?php echo $telefone; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_foto)){echo "has-error";}?>">
                            <label for="inputFoto" class="control-label">Foto
                                <?php if (!empty($erro_foto)){echo $erro_foto;} ?>
                            </label>
                            <img src="<?php $foto; ?>" alt="" id="foto" width="100" height="100"/>
                            <script>
                                document.getElementById('foto').src = "<?php echo $foto;?>";
                            </script>
                        </div>

                        <div class="form-group col-xs-3">
                            <label>TAG's</label>
                            <?php
                                $conn = mysqli_connect($servername, $username, $password);
                                if (!$conn) {
                                   die("Connection failed: " . mysqli_connect_error());
                                }

                                $sql = "USE $dbname";
                                if (!mysqli_query($conn, $sql)) {
                                    die("Error acess database: " . mysqli_error($conn));
                                }
                                mysqli_query($conn, "SET NAMES 'utf8'");
                                $query = mysqli_query($conn, "SELECT tag, master FROM tag, usuario WHERE tag.cpf=usuario.cpf AND usuario.cpf='$cpfe'");
                                mysqli_close($conn);
                            ?>
                            <select class="form-control" name="tag" id="tag">
                                <?php while($dep = mysqli_fetch_array($query)) { ?>
                                    <option value="<?php echo $dep["tag"] ?>"><?php if($dep["master"]==1) echo $dep["tag"]."M";else echo $dep["tag"] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <?php
                            require 'db_credentials.php';
                            $conn = mysqli_connect($servername, $username, $password);
                            $sql = "USE $dbname";
                            mysqli_query($conn, $sql);
                            $sql = "SELECT tag FROM tag WHERE tag.cpf='$cpfe'";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result)>0):
                         ?>
                            <label>:</label><br>
                            <input class="btn btn1" type="submit" name="editag" value="Editar"></input>
                        <?php mysqli_close($conn); endif; ?>
                    </fieldset>

                    <fieldset>
                        <legend>Endereço</legend>

                        <div class="form-group col-xs-3">
                            <label for="inputCep" class="control-label">CEP(Só numeros)</label>
                            <input disabled id="cep" class="form-control input-sm" type="text" placeholder="CEP" name="cep" value="<?php echo $cep; ?>">
                        </div>

                        <div class="form-group col-xs-3">
                            <label for="inputEstado" class="control-label">Estado</label>
                            <input disabled id="estado" class="form-control input-sm" type="text" placeholder="Estado" name="estado" value="<?php echo $estado; ?>">
                        </div>

                        <div class="form-group col-xs-3">
                            <label for="inputCidade" class="control-label">Cidade</label>
                            <input disabled id="cidade" class="form-control input-sm" type="text" placeholder="cidade" name="cidade" value="<?php echo $cidade; ?>">
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="inputRua" class="control-label">Rua</label>
                            <input disabled id="rua" class="form-control input-sm" type="text" placeholder="Rua" name="rua" value="<?php echo $rua; ?>">
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="inputBairro" class="control-label">Bairro</label>
                            <input disabled id="bairro" class="form-control input-sm" type="text" placeholder="Bairro" name="bairro" value="<?php echo $bairro; ?>">
                        </div>

                        <div class="form-group col-xs-1">
                            <label for="inputNumero" class="control-label">Numero</label>
                            <input disabled id="numero" class="form-control input-sm" type="number" placeholder="Numero" name="numero" value="<?php echo $numero; ?>">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Dados empresariais</legend>

                        <div class="form-group col-xs-3 <?php if(!empty($erro_departamento)){echo "has-error";}?>">
                            <label for="selectDepartamento" class="control-label">Departamento
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
                              document.getElementById('departamento').value = "<?php echo $departamento;?>";
                            </script>
                        </div>

                        <div class="form-group col-xs-3 <?php if(!empty($erro_cargo)){echo "has-error";}?>">
                            <label for="selectCargo" class="control-label">Cargo
                                <?php if (!empty($erro_cargo)){echo $erro_cargo;} ?>
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
                                $query = mysqli_query($conn, "SELECT idCargo, nome FROM Cargo");
                                mysqli_close($conn);
                            ?>
                            <select style="color:black;" class="form-control input-sm" name="cargo" id="cargo">
                                <?php while($dep = mysqli_fetch_array($query)) { ?>
                                    <option value="<?php echo $dep['idCargo'] ?>"><?php echo $dep['nome'] ?></option>
                                <?php } ?>
                            </select>
                            <script type="text/javascript">
                              document.getElementById('cargo').value = "<?php echo $cargo;?>";
                            </script>
                        </div>

                        <div class="form-group col-xs-3 <?php if(!empty($erro_filial)){echo "has-error";}?>">
                            <label for="selectFilial" class="control-label">Filial
                                <?php if (!empty($erro_filial)){echo $erro_filial;} ?>
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
                                $query = mysqli_query($conn, "SELECT idFilial, nome FROM Filial");
                                mysqli_close($conn);
                            ?>
                            <select style="color:black;" class="form-control input-sm" name="filial" id="filial">
                                <?php while($dep = mysqli_fetch_array($query)) { ?>
                                    <option value="<?php echo $dep['idFilial'] ?>"><?php echo $dep['nome'] ?></option>
                                <?php } ?>
                            </select>
                            <script type="text/javascript">
                              document.getElementById('filial').value = "<?php echo $filial;?>";
                            </script>
                        </div>

                        <div class="form-group <?php if(!empty($erro_tipo)){echo "has-error";}?>">
                            <div class="radio-inline">
                                <label><input type="radio" name="tipo" id="radioUsuario" value="usuario" <?php echo ($tipo == "usuario" ? "checked" : "") ?>>Usuário</label>
                            </div>

                            <div class="radio-inline">
                                <label><input type="radio" name="tipo" id="radioAdministrador" value="administrador" <?php echo ($tipo == "administrador" ? "checked" : "") ?>>Administrador
                                    <?php if (!empty($erro_tipo)){echo $erro_tipo;} ?>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    <input class="btn btn1" type="submit" name="excluir" value="Excluir cadastro"></input>
                    <input class="btn btn1" type="submit" name="salvar" value="Salvar alterações"></input>
                </form>
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
