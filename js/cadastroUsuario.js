$(document).ready( function() {
   /* Executa a requisição quando o campo CEP perder o foco */
   $('#cep').blur(function(){
           /* Configura a requisição AJAX */
           $.ajax({
                url : 'consultar_cep.php', /* URL que será chamada */
                type : 'POST', /* Tipo da requisição */
                data: 'cep=' + $('#cep').val(), /* dado que será enviado via POST */
                dataType: 'json', /* Tipo de transmissão */
                success: function(data){
                    if(data.sucesso == 1){
                        $('#rua').val(data.rua);
                        $('#cidade').val(data.cidade);
                        $('#bairro').val(data.bairro);
                        $('#estado').val(data.estado);

                        $('#numero').focus();
                    }
                }
           });
   return false;
   })
});
$(function()
{
    //Executa a requisição quando o campo username perder o foco
    $('#cpf').blur(function()
    {
        var cpf = $('#cpf').val().replace(/[^0-9]/g, '').toString();

        if( cpf.length == 11 )
        {
            var v = [];

            //Calcula o primeiro dígito de verificação.
            v[0] = 1 * cpf[0] + 2 * cpf[1] + 3 * cpf[2];
            v[0] += 4 * cpf[3] + 5 * cpf[4] + 6 * cpf[5];
            v[0] += 7 * cpf[6] + 8 * cpf[7] + 9 * cpf[8];
            v[0] = v[0] % 11;
            v[0] = v[0] % 10;

            //Calcula o segundo dígito de verificação.
            v[1] = 1 * cpf[1] + 2 * cpf[2] + 3 * cpf[3];
            v[1] += 4 * cpf[4] + 5 * cpf[5] + 6 * cpf[6];
            v[1] += 7 * cpf[7] + 8 * cpf[8] + 9 * v[0];
            v[1] = v[1] % 11;
            v[1] = v[1] % 10;
            document.getElementById('lcpf').style.color = "white";
            document.getElementById('lcpf').innerHTML = '*CPF';

            //Retorna Verdadeiro se os dígitos de verificação são os esperados.
            if ( (v[0] != cpf[9]) || (v[1] != cpf[10]) )
            {
                document.getElementById('lcpf').style.color = "red";
                document.getElementById('lcpf').innerHTML = 'CPF é inválido';

                $('#cpf').val('');
            }else{
                document.getElementById('lcpf').style.color = "white";
                document.getElementById('lcpf').innerHTML = '*CPF';
            }
        }
        else
        {
            document.getElementById('lcpf').style.color = "red";
            document.getElementById('lcpf').innerHTML = 'CPF é inválido';

            $('#cpf').val('');
        }
    });
});
$(function()
{
    //Executa a requisição quando o campo username perder o foco
    $('#nome').blur(function()
    {
        var nome = $('#nome').val();

        if(nome == null || nome == ""){
            document.getElementById('lnome').style.color = "red";
            document.getElementById('lnome').innerHTML = 'Nome é obrigatório';
        }else{
            document.getElementById('lnome').style.color = "white";
            document.getElementById('lnome').innerHTML = '*Nome completo';
        }
    });
});
function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}
$(function()
{
    //Executa a requisição quando o campo username perder o foco
    $('#email').blur(function()
    {
        var email = $('#email').val();

        if(email == null || email == ""){
            document.getElementById('lemail').style.color = "red";
            document.getElementById('lemail').innerHTML = 'Email é obrigatório';
        }else{
            if( !validateEmail(email)) {
                document.getElementById('lemail').style.color = "red";
                document.getElementById('lemail').innerHTML = 'Email incorreto';
            }else{
                document.getElementById('lemail').style.color = "white";
                document.getElementById('lemail').innerHTML = '*Email';
            }
        }
    });
});
var reDigits = /^\d+$/;
function numInteiro(pStr)
{
	if (reDigits.test(pStr))
	{
		return true;
	}
	else
	if (pStr != null && pStr != "")
	{
		return false;
	}
}
$(function()
{
    //Executa a requisição quando o campo username perder o foco
    $('#numero').blur(function()
    {
        var numero = $('#numero').val();

        if(!numInteiro(numero)){
            document.getElementById('lnumero').style.color = "red";
            document.getElementById('lnumero').innerHTML = 'Numero incorreto';
        }else{
            document.getElementById('lnumero').style.color = "white";
            document.getElementById('lnumero').innerHTML = 'Numero';
        }
    });
});
function verificaData(Data)
 {
  Data = Data.substring(0,10);

  var dma = -1;
  var data = Array(3);
  var ch = Data.charAt(0);
  for(i=0; i < Data.length && (( ch >= '0' && ch <= '9' ) || ( ch == '/' && i != 0 ) ); ){
   data[++dma] = '';
   if(ch!='/' && i != 0) return false;
   if(i != 0 ) ch = Data.charAt(++i);
   if(ch=='0') ch = Data.charAt(++i);
   while( ch >= '0' && ch <= '9' ){
    data[dma] += ch;
    ch = Data.charAt(++i);
   }
  }
  if(ch!='') return false;
  if(data[0] == '' || isNaN(data[0]) || parseInt(data[0]) < 1) return false;
  if(data[1] == '' || isNaN(data[1]) || parseInt(data[1]) < 1 || parseInt(data[1]) > 12) return false;
  if(data[2] == '' || isNaN(data[2]) || ((parseInt(data[2]) < 0 || parseInt(data[2]) > 99 ) && (parseInt(data[2]) < 1900 || parseInt(data[2]) > 9999))) return false;
  if(data[2] < 50) data[2] = parseInt(data[2]) + 2000;
  else if(data[2] < 100) data[2] = parseInt(data[2]) + 1900;
  switch(parseInt(data[1])){
   case 2: { if(((parseInt(data[2])%4!=0 || (parseInt(data[2])%100==0 && parseInt(data[2])%400!=0)) && parseInt(data[0]) > 28) || parseInt(data[0]) > 29 ) return false; break; }
   case 4: case 6: case 9: case 11: { if(parseInt(data[0]) > 30) return false; break;}
   default: { if(parseInt(data[0]) > 31) return false;}
  }
  return true;

}
$(function()
{
    //Executa a requisição quando o campo username perder o foco
    $('#nascimento').blur(function()
    {
        var nascimento = $('#nascimento').val();

        if(!verificaData(nascimento)){
            document.getElementById('lnascimento').style.color = "red";
            document.getElementById('lnascimento').innerHTML = 'Data incorreta';
        }else{
            document.getElementById('lnascimento').style.color = "white";
            document.getElementById('lnascimento').innerHTML = 'Data de nascimento';
        }
    });
});
function validaFormulario(){
    var nome = document.forms["formularioCadastro"]["nome"].value;
	var cpf = document.forms["formularioCadastro"]["cpf"].value;
	var email = document.forms["formularioCadastro"]["email"].value;
	var senha = document.forms["formularioCadastro"]["senha"].value;
    var confirmacao = document.forms["formularioCadastro"]["confirmacao"].value;
	var departamento = document.forms["formularioCadastro"]["departamento"].value;
	var filial = document.forms["formularioCadastro"]["filial"].value;
    var cargo = document.forms["formularioCadastro"]["cargo"].value;
    var numero = document.forms["formularioCadastro"]["numero"].value;
    var nascimento = document.forms["formularioCadastro"]["nascimento"].value;
    if(nome == null || nome == ""){
        document.getElementById('lnome').style.color = "red";
        document.getElementById('lnome').innerHTML = 'Nome é obrigatório';
        return false;
    }else{
        document.getElementById('lnome').style.color = "white";
        document.getElementById('lnome').innerHTML = '*Nome completo';
    }
    if(cpf == null || cpf == ""){
        document.getElementById('lcpf').style.color = "red";
        document.getElementById('lcpf').innerHTML = 'CPF é obrigatório';
        return false;
	}else{
        document.getElementById('lcpf').style.color = "white";
        document.getElementById('lcpf').innerHTML = '*CPF';
    }
    if(email == null || email == ""){
        document.getElementById('lemail').style.color = "red";
        document.getElementById('lemail').innerHTML = 'Email é obrigatório';
        return false;
	}else{
        document.getElementById('lemail').style.color = "white";
        document.getElementById('lemail').innerHTML = '*Email';
    }
    if(senha == null || senha == ""){
        document.getElementById('lsenha').style.color = "red";
        document.getElementById('lsenha').innerHTML = 'Senha é obrigatória';
        return false;
	}else{
        document.getElementById('lsenha').style.color = "white";
        document.getElementById('lsenha').innerHTML = '*Senha';
    }
    if(confirmacao == null || confirmacao == ""){
        document.getElementById('lconfirmacao').style.color = "red";
        document.getElementById('lconfirmacao').innerHTML = 'Confirmação de senha é obrigatória';
        return false;
	}else{
        document.getElementById('lconfirmacao').style.color = "white";
        document.getElementById('lconfirmacao').innerHTML = '*Confirmação de senha';
    }
    if(departamento == 0){
        document.getElementById('ldepartamento').style.color = "red";
        document.getElementById('ldepartamento').innerHTML = 'Departamento é obrigatório';
        return false;
	}else{
        document.getElementById('ldepartamento').style.color = "white";
        document.getElementById('ldepartamento').innerHTML = '*Departamento';
    }
    if(cargo == 0){
        document.getElementById('lcargo').style.color = "red";
        document.getElementById('lcargo').innerHTML = 'Cargo é obrigatório';
        return false;
	}else{
        document.getElementById('lcargo').style.color = "white";
        document.getElementById('lcargo').innerHTML = '*Cargo';
    }
    if(filial == 0){
        document.getElementById('lfilial').style.color = "red";
        document.getElementById('lfilial').innerHTML = 'Filial é obrigatória';
        return false;
	}else{
        document.getElementById('lfilial').style.color = "white";
        document.getElementById('lfilial').innerHTML = '*Filial';
    }
    if(numero != ""){
        if(!numInteiro(numero)){
            document.getElementById('lnumero').style.color = "red";
            document.getElementById('lnumero').innerHTML = 'Numero incorreto';
            return false;
        }else{
            document.getElementById('lnumero').style.color = "white";
            document.getElementById('lnumero').innerHTML = 'Numero';
        }
    }
    if(nascimento != ""){
        if(!verificaData(nascimento)){
            document.getElementById('lnascimento').style.color = "red";
            document.getElementById('lnascimento').innerHTML = 'Data incorreta';
            return false;
        }else{
            document.getElementById('lnascimento').style.color = "white";
            document.getElementById('lnascimento').innerHTML = 'Data de nascimento';
        }
    }
    return true;
}
