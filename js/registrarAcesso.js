$(function()
{
    //Executa a requisição quando o campo username perder o foco
    $('#exibicao').blur(function()
    {
        var tag = $('#exibicao').val();

        if(tag == null || tag == ""){
            document.getElementById('ltag').style.color = "red";
            document.getElementById('ltag').innerHTML = 'TAG é obrigatória';
        }else{
            document.getElementById('ltag').style.color = "white";
            document.getElementById('ltag').innerHTML = 'TAG';
        }
    });
});
function validaTag(){
    var tag = document.forms["calc"]["exibicao"].value;
    if(tag == null || tag == ""){
        document.getElementById('ltag').style.color = "red";
        document.getElementById('ltag').innerHTML = 'TAG é obrigatória';
        return false;
    }else{
        document.getElementById('ltag').style.color = "white";
        document.getElementById('ltag').innerHTML = 'TAG';
    }
    return true;
}
