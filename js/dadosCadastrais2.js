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
    $('#nome').blur(function()
    {
        var nome = $('#nome').val();

        if(nome == null || nome == ""){
            document.getElementById('lnome').style.color = "red";
            document.getElementById('lnome').innerHTML = 'Nome é obrigatório';
        }else{
            document.getElementById('lnome').style.color = "white";
            document.getElementById('lnome').innerHTML = 'Nome completo';
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
                document.getElementById('lemail').innerHTML = 'Email';
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

        if(numero != ""){
            if(!numInteiro(numero)){
                document.getElementById('lnumero').style.color = "red";
                document.getElementById('lnumero').innerHTML = 'Numero incorreto';
            }else{
                document.getElementById('lnumero').style.color = "white";
                document.getElementById('lnumero').innerHTML = 'Numero';
            }
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

        if(nascimento != ""){
            if(!verificaData(nascimento)){
                document.getElementById('lnascimento').style.color = "red";
                document.getElementById('lnascimento').innerHTML = 'Data incorreta';
            }else{
                document.getElementById('lnascimento').style.color = "white";
                document.getElementById('lnascimento').innerHTML = 'Data de nascimento';
            }
        }
    });
});
function validaFormulario(){
    var nome = document.forms["formularioCadastro"]["nome"].value;
	var email = document.forms["formularioCadastro"]["email"].value;
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
        document.getElementById('lnome').innerHTML = 'Nome completo';
    }
    if(email == null || email == ""){
        document.getElementById('lemail').style.color = "red";
        document.getElementById('lemail').innerHTML = 'Email é obrigatório';
        return false;
    }else{
        if( !validateEmail(email)) {
            document.getElementById('lemail').style.color = "red";
            document.getElementById('lemail').innerHTML = 'Email incorreto';
            return false;
        }else{
            document.getElementById('lemail').style.color = "white";
            document.getElementById('lemail').innerHTML = 'Email';
        }
    }
    if(senha == null || senha == ""){
        document.getElementById('lsenha').style.color = "red";
        document.getElementById('lsenha').innerHTML = 'Senha é obrigatória';
        return false;
	}else{
        document.getElementById('lsenha').style.color = "white";
        document.getElementById('lsenha').innerHTML = 'Senha';
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
