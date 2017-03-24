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
    $('#data').blur(function()
    {
        var nascimento = $('#data').val();

        if(!verificaData(nascimento)){
            document.getElementById('ldata').style.color = "red";
            document.getElementById('ldata').innerHTML = 'Data incorreta';
        }else{
            document.getElementById('ldata').style.color = "white";
            document.getElementById('ldata').innerHTML = 'Data';
        }
    });
});
function validaFormulario(){
    var data = document.forms["formularioCadastro"]["data"].value;
    if(data != ""){
        if(!verificaData(data)){
            document.getElementById('ldata').style.color = "red";
            document.getElementById('ldata').innerHTML = 'Data incorreta';
            return false;
        }else{
            document.getElementById('ldata').style.color = "white";
            document.getElementById('ldata').innerHTML = 'Data';
        }
    }else{
        document.getElementById('ldata').style.color = "red";
        document.getElementById('ldata').innerHTML = 'Data incorreta';
        return false;
    }
    return true;
}
