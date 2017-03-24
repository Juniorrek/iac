<?php
    include 'crypt.php';
    session_start();
    $user = $_SESSION["user"];

    $nome = $user["nome"];
    $cpf = $user["CPF"];
    $email = $user["email"];
    $nome = $user["nome"];
    $telefone = $user["Telefone"];
    $senha = $user["Senha"];
    $foto = $user["foto"];
    if($foto == "imagens/") $foto = "imagens/semfoto.png";
    $nascimento = $user["nasc"][8].$user["nasc"][9]."/".$user["nasc"][5].$user["nasc"][6]."/".$user["nasc"][0].$user["nasc"][1].$user["nasc"][2]
    .$user["nasc"][3];
    $idEndereco = $user["Endereco"];

    require 'db_credentials.php';
    $conn = mysqli_connect($servername, $username, $password);
    $sql = "USE $dbname";
    mysqli_query($conn, $sql);
    $sql = "SELECT numero, CEP, idEndereco, cidade, bairro, estado, rua FROM endereco, usuario WHERE usuario.endereco=endereco.idEndereco AND usuario.cpf='$cpf'";
    $result = mysqli_query($conn, $sql);
    $endereco = mysqli_fetch_assoc($result);
    $cep = $endereco["CEP"];
    $estado = $endereco["estado"];
    $cidade = $endereco["cidade"];
    $rua = $endereco["rua"];
    $bairro = $endereco["bairro"];
    $numero = $endereco["numero"];
    mysqli_close($conn);

    $departamento = $user["departamento"];
    $filial = $user["filial"];
    $cargo = $user["cargo"];
    if($user["adm"] == 0)
        $tipo = "usuario";
    else
        $tipo = "administrador";

    $erro = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(empty($_POST["nome"])){
            $erro_nome = "é obrigatório.";
            $erro = true;
        }else{
            if(!preg_match("/^[a-zA-ZãÃáÁàÀêÊéÉèÈíÍìÌôÔõÕóÓòÒúÚùÙûÛçÇºª ]+$/", 'ãÃáÁàÀ    êÊéÉèÈíÍìÌôÔõÕóÓòÒúÚùÙûÛçÇºª')){
              $erro_nome = "invalido.";
              $erro = true;
            }
        }

        if(empty($_POST["email"])){
            $erro_email = "é obrigatório.";
            $erro = true;
        }else{
            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
              $erro_email = "invalido.";
              $erro = true;
            }
        }

        if(empty($_POST["nascimento"])){
            $nascimento = $user["nasc"][8].$user["nasc"][9]."/".$user["nasc"][5].$user["nasc"][6]."/".$user["nasc"][0].$user["nasc"][1].$user["nasc"][2]
            .$user["nasc"][3];
             $erro = true;
         }

         if($_POST["departamento"]==0){
           $erro_departamento = "é obrigatório.";
           $erro = true;
       }else {
           $departamento = $_POST["departamento"];
       }

         if($_POST["cargo"]==0){
           $erro_cargo = "é obrigatório.";
           $erro = true;
       }else{
           $cargo = $_POST["cargo"];
       }

         if($_POST["filial"]==0){
           $erro_filial = "é obrigatório.";
           $erro = true;
       }else {
           $filial = $_POST["filial"];
       }

       if($_POST["senha"] != $_POST["confirmacao"]){
           $erro = true;
           $erro_senha = "diferente.";
           $erro_confirmacao = "diferente.";
       }

        $target_dir = $_SERVER['DOCUMENT_ROOT']."imagens/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $relative_file = "imagens/".basename($_FILES["foto"]["name"]);
        if( $target_file != $_SERVER['DOCUMENT_ROOT']."imagens/"){//vazio
            if($imageFileType != "jpg" && $imageFileType != "png" ) {
                $erro_foto = "somente jpg ou png";
                $erro = true;
            } else {
                if ($_FILES["foto"]["size"] > 1000000) {
                    $erro_foto = "tamanho máximo 1MB";
                    $erro = true;
                }
            }
        }
        if($target_file != $_SERVER['DOCUMENT_ROOT']."imagens/") $foto = $relative_file;

        if(!$erro){
            if(isset($_POST["salvar"])){
                require 'db_credentials.php';
                $conn = mysqli_connect($servername, $username, $password);
                $sql = "USE $dbname";
                mysqli_query($conn, $sql);

                $nome = $_SESSION["user"]["nome"] = $_POST["nome"];
                $email = $_SESSION["user"]["email"] = $_POST["email"];
                $senha = $_POST["senha"];
                $nascimento = $_SESSION["user"]["nasc"] = $_POST["nascimento"][6].$_POST["nascimento"][7].$_POST["nascimento"][8].$_POST["nascimento"][9].'/'.$_POST["nascimento"][3].$_POST["nascimento"][4]."/"
                    .$_POST["nascimento"][0].$_POST["nascimento"][1];
                $telefone = $_SESSION["user"]["Telefone"] = $_POST["telefone"];


                $cep = $_POST["cep"];
                $estado = $_POST["estado"];
                $cidade = $_POST["cidade"];
                $rua = $_POST["rua"];
                $bairro = $_POST["bairro"];
                $numero = $_POST["numero"];

                $cargo = $_SESSION["user"]["cargo"] = $_POST["cargo"];
                $departamento = $_SESSION["user"]["departamento"] = $_POST["departamento"];
                $filial = $_SESSION["user"]["filial"] = $_POST["filial"];

                if ($senha != $user["Senha"]){ /*$senha = md5($senha);*/$senha = encryptIt( $senha ); $_SESSION["user"]["Senha"] = $senha;}
                else $senha = $user["Senha"];

                mysqli_query($conn, "SET NAMES 'utf8'");
                $sql = "UPDATE endereco SET endereco.numero = '$numero', endereco.CEP = '$cep', endereco.cidade = '$cidade', endereco.bairro = '$bairro', endereco.estado = '$estado', endereco.rua = '$rua'
                        WHERE endereco.idEndereco = '$idEndereco';";
                mysqli_query($conn, $sql);
                if($target_file != $_SERVER['DOCUMENT_ROOT']."imagens/"){
                    $_SESSION["user"]["foto"] = $foto;
                    $sql = "UPDATE usuario SET usuario.nome = '$nome', usuario.nasc = '$nascimento', usuario.foto = '$foto', usuario.cargo = '$cargo', usuario.filial = '$filial', usuario.departamento = '$departamento', usuario.telefone = '$telefone', usuario.Senha = '$senha',
                    usuario.email = '$email'
                            WHERE usuario.CPF = '$cpf';";
                    move_uploaded_file($_FILES["foto"]["tmp_name"], $relative_file);
                }else {
                    $sql = "UPDATE usuario SET usuario.nome = '$nome', usuario.nasc = '$nascimento', usuario.cargo = '$cargo', usuario.filial = '$filial', usuario.departamento = '$departamento', usuario.telefone = '$telefone', usuario.Senha = '$senha',
                    usuario.email = '$email'
                            WHERE usuario.CPF = '$cpf';";
                }
                if (!mysqli_query($conn, $sql)) {
                    die("Error acess database: " . mysqli_error($conn));
                }
                mysqli_close($conn);
                $nascimento = $_SESSION["user"]["nasc"][8].$_SESSION["user"]["nasc"][9]."/".$_SESSION["user"]["nasc"][5].$_SESSION["user"]["nasc"][6].
                "/".$_SESSION["user"]["nasc"][0].$_SESSION["user"]["nasc"][1].$_SESSION["user"]["nasc"][2].$_SESSION["user"]["nasc"][3];
            }
        }
    }
 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Dados cadastrais administrador</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
        <script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js"/></script>
        <script type="text/javascript">$(document).ready(function(){	$("#cep").mask("99.999-999");});</script>
        <script type="text/javascript">$(document).ready(function(){	$("#nascimento").mask("99/99/9999");});</script>
        <script type="text/javascript">$(document).ready(function(){	$("#telefone").mask("(99) 9999-9999");});</script>

        <style media="screen">

            .btn2 {
                background: #008a8a;
                text-shadow: 1px 1px 3px #005858;
                font-family: Arial;
                color: #ffffff;
                border: solid #005758 1px;
                text-decoration: none;
                border-radius: 0;
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
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" onsubmit="return validaFormulario()" name="formularioCadastro">
                    <fieldset>
                        <legend>Dados pessoais</legend>

                        <div class="form-group <?php if(!empty($erro_nome)){echo "has-error";}?>">
                            <label for="inputNome" class="control-label" id="lnome">Nome completo
                                <?php if (!empty($erro_nome)){echo $erro_nome;} ?>
                            </label>
                            <input class="form-control input-sm" id="nome" name="nome" type="text" placeholder="Nome completo" value="<?php echo $nome; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_cpf)){echo "has-error";}?>">
                            <label for="inputCpf" class="control-label">CPF
                                <?php if (!empty($erro_cpf)){echo $erro_cpf;} ?>
                            </label>
                            <input disabled class="form-control input-sm" name="cpf" type="text" placeholder="CPF" value="<?php echo $cpf; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_email)){echo "has-error";}?>">
                            <label for="inputEmail" class="control-label" id="lemail">Email
                                <?php if (!empty($erro_email)){echo $erro_email;} ?>
                            </label>
                            <input class="form-control input-sm" name="email" id="email" type="text" placeholder="Email" value="<?php echo $email; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_senha)){echo "has-error";}?>">
                            <label for="inputSenha" class="control-label">Senha
                                <?php if (!empty($erro_senha)){echo $erro_senha;} ?>
                            </label>
                            <input class="form-control input-sm" name="senha" type="password" placeholder="Senha" value="<?php echo $senha; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_confirmacao)){echo "has-error";}?>">
                            <label for="inputConfirmacao" class="control-label" id="lconfirmacao">Confirmação de senha
                                <?php if (!empty($erro_confirmacao)){echo $erro_confirmacao;} ?>
                            </label>
                            <input class="form-control input-sm" name="confirmacao" type="password" placeholder="Confirmacao">
                        </div>

                        <div class="form-group col-xs-5">
                            <label id="lnascimento">Data de nascimento</label>
                            <input class="form-control input-sm" type="text" name="nascimento" id="nascimento" value = "<?php echo $nascimento;?>">
                        </div>

                        <div class="form-group col-xs-5">
                            <label for="inputTelefone" class="control-label">Telefone</label>
                            <input class="form-control input-sm" type="text" placeholder="Telefone" id="telefone" name="telefone" value="<?php echo $telefone; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_foto)){echo "has-error";}?>">
                            <label for="inputFoto" class="control-label">Foto
                                <?php if (!empty($erro_foto)){echo $erro_foto;} ?>
                            </label>
                            <input class="form-control input-sm" type="file" name="foto">
                            <img src="<?php $foto; ?>" alt="" id="foto" width="100" height="100"/>
                            <script>
                                document.getElementById('foto').src = "<?php echo $foto;?>";
                            </script>
                        </div>

                        <div class="form-group col-xs-5">
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
                                $query = mysqli_query($conn, "SELECT tag FROM tag, usuario WHERE tag.cpf=usuario.cpf AND usuario.cpf='$cpf'");
                                mysqli_close($conn);
                            ?>
                            <select class="form-control">
                                <?php while($dep = mysqli_fetch_array($query)) { ?>
                                    <option><?php echo $dep["tag"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Endereço</legend>

                        <div class="form-group col-xs-3">
                            <label for="inputCep" class="control-label">CEP</label>
                            <input id="cep" class="form-control input-sm" type="text" placeholder="CEP" name="cep" value="<?php echo $cep; ?>">
                        </div>

                        <div class="form-group col-xs-3">
                            <label for="inputEstado" class="control-label">Estado</label>
                            <input id="estado" class="form-control input-sm" type="text" placeholder="Estado" name="estado" value="<?php echo $estado; ?>">
                        </div>

                        <div class="form-group col-xs-3">
                            <label for="inputCidade" class="control-label">Cidade</label>
                            <input id="cidade" class="form-control input-sm" type="text" placeholder="cidade" name="cidade" value="<?php echo $cidade; ?>">
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="inputRua" class="control-label">Rua</label>
                            <input id="rua" class="form-control input-sm" type="text" placeholder="Rua" name="rua" value="<?php echo $rua; ?>">
                        </div>

                        <div class="form-group col-xs-4">
                            <label for="inputBairro" class="control-label">Bairro</label>
                            <input id="bairro" class="form-control input-sm" type="text" placeholder="Bairro" name="bairro" value="<?php echo $bairro; ?>">
                        </div>

                        <div class="form-group col-xs-1">
                            <label for="inputNumero" class="control-label" id="lnumero">Número</label>
                            <input id="numero" class="form-control input-sm" type="number" placeholder="Numero" name="numero" value="<?php echo $numero; ?>">
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
                                <option>Selecione...</option>
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
                                <option>Selecione...</option>
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
                                <option>Selecione...</option>
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
                                <label><input disabled type="radio" name="tipo" id="radioUsuario" value="usuario" <?php echo ($tipo == "usuario" ? "checked" : "") ?>>Usuário</label>
                            </div>

                            <div class="radio-inline">
                                <label><input disabled type="radio" name="tipo" id="radioAdministrador" value="administrador" <?php echo ($tipo == "administrador" ? "checked" : "") ?>>Administrador
                                    <?php if (!empty($erro_tipo)){echo $erro_tipo;} ?>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <input class="btn btn2" type="submit" name="salvar" value="Salvar alterações"></input>
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
        <script src="js/dadosCadastrais.js"></script>
    </body>
</html>
