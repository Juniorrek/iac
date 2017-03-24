<?php
require 'crypt.php';
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

$nome = $cpf = $email = $departamento = $cargo = $filial = $tipo = $foto = $telefone =
$estado = $cidade = $rua = $bairro = $data = $adm = $senha = $confirmacao = $cep = $numero = "";
$erro = false; $erro2 = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if(empty($_POST["nome"])){
    $erro_nome = "é obrigatório.";
    $erro = true;
  }else{
    $nome = verifica_campo($_POST["nome"]);
    if(!preg_match("/^[a-zA-ZãÃáÁàÀêÊéÉèÈíÍìÌôÔõÕóÓòÒúÚùÙûÛçÇºª ]+$/", 'ãÃáÁàÀ    êÊéÉèÈíÍìÌôÔõÕóÓòÒúÚùÙûÛçÇºª')){
      $erro_nome = "invalido.";
      $erro = true;
    }
  }

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

  if(empty($_POST["email"])){
    $erro_email = "é obrigatório.";
    $erro = true;
  }else{
    $email = verifica_campo($_POST["email"]);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $erro_email = "invalido.";
      $erro = true;
    }
  }

  if(!empty($_POST["telefone"])){
    $telefone = $_POST["telefone"];
  }

  if(empty($_POST["senha"])){
    $erro_senha = "é obrigatório.";
    $erro = true;
  }else{
    $senha = verifica_campo($_POST["senha"]);
  }

  if(empty($_POST["confirmacao"])){
    $erro_confirmacao = "é obrigatório.";
    $erro = true;
  }else{
    $confirmacao = verifica_campo($_POST["senha"]);
  }

  if($_POST["senha"] != $_POST["confirmacao"]){
      $erro = true;
      $erro_confirmacao = "diferente.";
      $erro_senha = "diferente.";
  }


 if(!empty($_POST["nascimento"])){
      /*$data = substr($_POST["nascimento"], 8, 2);
      $data .= '/';
      $data .= substr($_POST["nascimento"], 5, 2);
      $data .= '/';
      $data .= substr($_POST["nascimento"], 0, 4);*/
      $data = $_POST["nascimento"];
  }else{
      $data = "1997/08/15";
  }

  if(!empty($_POST["cep"])){
    $cep = $_POST["cep"];
}else{
    $cep = " ";
}
  if(!empty($_POST["estado"])){
    $estado = $_POST["estado"];
  }else{
      $estado = " ";
  }
  if(!empty($_POST["cidade"])){
    $cidade = $_POST["cidade"];
  }else{
    $cidade = " ";
  }
  if(!empty($_POST["rua"])){
    $rua = $_POST["rua"];
  }else{
     $rua = " ";
  }
  if(!empty($_POST["bairro"])){
    $bairro = $_POST["bairro"];
  }else{
      $bairro = " ";
  }
  if(!empty($_POST["numero"])){
    $numero = $_POST["numero"];
  }else{
      $numero = 0;
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

  if(empty($_POST["tipo"])){
    $erro_tipo = "Tipo é obrigatório.";
    $erro = true;
  }else{
    $tipo = verifica_campo($_POST["tipo"]);
    $adm = $tipo == "usuario" ? 0 : true;
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
  echo $telefone;
  if(!$erro) {
     require 'db_credentials.php';

     // Create connection
     $conn = mysqli_connect($servername, $username, $password);
     // Check connection
     if (!$conn) {
         die("Connection failed: " . mysqli_connect_error());
     }
     // Choose database
     $sql = "USE $dbname";
     if (!mysqli_query($conn, $sql)) {
         die("Error acess database: " . mysqli_error($conn));
     }
     $sql = "SELECT CPF FROM Usuario WHERE CPF='$cpf'";
     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result)>0) {
         // output data of each row
         $erro2=1;
     } else {
         mysqli_query($conn, "SET NAMES 'utf8'");
         $sql = "INSERT INTO Endereco (CEP, cidade, bairro, estado, rua, numero)
                 VALUES ( '$cep', '$cidade', '$bairro', '$estado', '$rua', '$numero')";
        if( !mysqli_query( $conn, $sql)){
            $erro2=1;
            die("Error acess database: " . mysqli_error($conn));
         }else {
             $last_id = mysqli_insert_id($conn);
             $senha = encryptIt( $senha );
             //$senha = md5($senha);
             $sql = "INSERT INTO $table (CPF, nome, nasc, foto, adm, cargo, filial, departamento, telefone, email, endereco, ativada, senha)
                     VALUES ( '$cpf', '$nome', '$data', '$relative_file', '$adm', '$cargo', '$filial', '$departamento', '$telefone', '$email', '$last_id', '0', '$senha')";
             if( !mysqli_query( $conn, $sql)){
                    $erro2=2;
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
             }else {
                 move_uploaded_file($_FILES["foto"]["tmp_name"], $relative_file);
                 $erro2=3;
             }
         }
     }
     mysqli_close($conn);
  }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Cadastro usuário</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="padrao.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
        <script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js"/></script>
        <script type="text/javascript">$(document).ready(function(){	$("#cpf").mask("999.999.999-99");});</script>
        <script type="text/javascript">$(document).ready(function(){	$("#cep").mask("99.999-999");});</script>
        <script type="text/javascript">$(document).ready(function(){	$("#nascimento").mask("99/99/9999");});</script>
        <script type="text/javascript">$(document).ready(function(){	$("#telefone").mask("(99) 9999-9999");});</script>

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

                <button onclick="window.location.href='telaInicialAcesso.php'" class="btn btn1" type="button" name="button">Voltar</button>
            </div>

            <div class="content">
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            		<?php if ($erro2 == 3): ?>
                        <div id="resultado" style="background: white ;" class="<?php echo "has-sucess"; ?>">
                            <label for="resultado" class="control-label">Sua solicitação foi registrada com sucesso, espere até um administrador aprova-lá.</label>
                        </div>
                    <?php endif; ?>
                    <?php if ($erro2 == 2): ?>
                        <div id="resultado" style="background: white ;" class="<?php echo "has-error"; ?>">
                            <label for="resultado" class="control-label">Erro ao cadastrar usuario.</label>
                        </div>
                    <?php endif; ?>
                    <?php if ($erro2 == 1): ?>
                        <div id="resultado" style="background: white ;" class="<?php echo "has-error"; ?>">
                            <label for="resultado" class="control-label">Erro ao cadastrar endereço, provavel cpf ja existente.</label>
                        </div>
                    <?php endif; ?>
        		<?php endif; ?>

                Após o preenchimento correto dos dados, suas informações serão arquivadas no banco de dados
                até a aprovação de um administrador. OBS: *São dados obrigatórios<!-- (parece meus curriculos) -->

                <form name="formularioCadastro" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" onsubmit="return validaFormulario()">
                    <fieldset>
                        <legend>Dados pessoais</legend>

                        <div class="form-group <?php if(!empty($erro_nome)){echo "has-error";}?>">
                            <label for="inputNome" class="control-label" id="lnome">*Nome completo
                                <?php if (!empty($erro_nome)){echo $erro_nome;} ?>
                            </label>
                            <input class="form-control input-sm" name="nome" id="nome" type="text" placeholder="Nome completo" value="<?php echo $nome; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_cpf)){echo "has-error";}?>">
                            <label for="inputCpf" class="control-label" id="lcpf">*CPF
                                <?php if (!empty($erro_cpf)){echo $erro_cpf;} ?>
                            </label>
                            <input class="form-control input-sm" name="cpf" type="text" placeholder="CPF" value="<?php echo $cpf; ?>" id="cpf">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_email)){echo "has-error";}?>">
                            <label for="inputEmail" class="control-label" id="lemail">*Email
                                <?php if (!empty($erro_email)){echo $erro_email;} ?>
                            </label>
                            <input class="form-control input-sm" name="email" id="email" type="text" placeholder="Email" value="<?php echo $email; ?>">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_senha)){echo "has-error";}?>">
                            <label for="inputSenha" class="control-label" id="lsenha">*Senha
                                <?php if (!empty($erro_senha)){echo $erro_senha;} ?>
                            </label>
                            <input class="form-control input-sm" name="senha" type="password" placeholder="Senha">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_confirmacao)){echo "has-error";}?>">
                            <label for="inputConfirmacao" class="control-label" id="lconfirmacao">*Confirmação de senha
                                <?php if (!empty($erro_confirmacao)){echo $erro_confirmacao;} ?>
                            </label>
                            <input class="form-control input-sm" name="confirmacao" type="password" placeholder="Confirmacao">
                        </div>

                        <div class="form-group col-xs-5">
                            <label id="lnascimento">Data de nascimento</label>
                            <input class="form-control input-sm" type="text" name="nascimento" id="nascimento">
                            <script>
                                document.getElementById('nascimento').value = "<?php echo $_POST['nascimento'];?>";
                            </script>
                        </div>

                        <div class="form-group col-xs-5">
                            <label for="inputTelefone" class="control-label">Telefone</label>
                            <input class="form-control input-sm" type="text" placeholder="Telefone" name="telefone" value="<?php echo $telefone; ?>" id="telefone">
                        </div>

                        <div class="form-group col-xs-5 <?php if(!empty($erro_foto)){echo "has-error";}?>">
                            <label for="inputFoto" class="control-label">Foto
                                <?php if (!empty($erro_foto)){echo $erro_foto;} ?>
                            </label>
                            <input class="form-control input-sm" type="file" name="foto" id="foto">
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
                            <label for="inputNumero" id="lnumero" class="control-label">Número</label>
                            <input id="numero" class="form-control input-sm" type="number" placeholder="Numero" name="numero" value="<?php echo $numero; ?>">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Dados empresariais</legend>

                        <div class="form-group col-xs-3 <?php if(!empty($erro_departamento)){echo "has-error";}?>">
                            <label for="selectDepartamento" class="control-label" id="ldepartamento">*Departamento
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
                                <option value="0">Selecione...</option>
                                <?php while($dep = mysqli_fetch_array($query)) { ?>
                                    <option value="<?php echo $dep['idDep'] ?>"><?php echo $dep['nome'] ?></option>
                                <?php } ?>
                            </select>
                            <script type="text/javascript">
                              document.getElementById('departamento').value = "<?php echo $_POST['departamento'];?>";
                            </script>
                        </div>

                        <div class="form-group col-xs-3 <?php if(!empty($erro_cargo)){echo "has-error";}?>">
                            <label for="selectCargo" class="control-label" id="lcargo">*Cargo
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
                                <option value="0">Selecione...</option>
                                <?php while($dep = mysqli_fetch_array($query)) { ?>
                                    <option value="<?php echo $dep['idCargo'] ?>"><?php echo $dep['nome'] ?></option>
                                <?php } ?>
                            </select>
                            <script type="text/javascript">
                              document.getElementById('cargo').value = "<?php echo $_POST['cargo'];?>";
                            </script>
                        </div>

                        <div class="form-group col-xs-3 <?php if(!empty($erro_filial)){echo "has-error";}?>">
                            <label for="selectFilial" class="control-label" id="lfilial">*Filial
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
                                <option value="0">Selecione...</option>
                                <?php while($dep = mysqli_fetch_array($query)) { ?>
                                    <option value="<?php echo $dep['idFilial'] ?>"><?php echo $dep['nome'] ?></option>
                                <?php } ?>
                            </select>
                            <script type="text/javascript">
                              document.getElementById('filial').value = "<?php echo $_POST['filial'];?>";
                            </script>
                        </div>

                        <div class="form-group <?php if(!empty($erro_tipo)){echo "has-error";}?>">
                            <div class="radio-inline">
                                <label><input type="radio" name="tipo" id="radioUsuario" value="usuario" <?php echo ($tipo == "usuario" ? "checked" : "") ?>>*Usuário</label>
                            </div>

                            <div class="radio-inline">
                                <label><input type="radio" name="tipo" id="radioAdministrador" value="administrador" <?php echo ($tipo == "administrador" ? "checked" : "") ?>>*Administrador
                                    <?php if (!empty($erro_tipo)){echo $erro_tipo;} ?>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <!-- onclick="window.location.href='telaInicialAcesso.html'" -->
                    <button class="btn btn2" type="submit" name="button">Cadastrar</button>
                </form>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                UNLOCK.inc Todos os direitos reservados.
            </div>
        </footer>

        <script src="js/cadastroUsuario.js"></script>

    </body>
</html>
