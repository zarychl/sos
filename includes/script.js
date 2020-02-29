function closeCardConf(cardid)
{
    var result = confirm("Czy napewno chcesz zakończyć tę kartę ?");
    if (result) {
        location.href = "przejazdyZamknij.php?idkarty="+cardid;
    }
}

function ucfirst(str,force){
    str=force ? str.toLowerCase() : str;
    return str.replace(/(\b)([a-zA-Z])/,
             function(firstLetter){
                return   firstLetter.toUpperCase();
             });
}

$(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });

    $(".fill-dysponent").click(function() {
        $('#przychodnia').val($(this).data("name"));
        $('#przychodnia').attr('readonly','readonly');
        $('#przychodnia').show();
        $('#przychodnia').removeAttr('disabled');

        $('#przychodnia-poz').attr('disabled', 'disabled');
        $('#przychodnia-poz').hide();
    });

    $(".fill-dysponent-poz").click(function() {
        $('#przychodnia').attr('disabled','disabled');
        $('#przychodnia').hide();
        $('#przychodnia-poz').removeAttr('disabled');
        $('#przychodnia-poz').show();
    });

    $(".diff-dysponent").click(function() {
        $('#przychodnia').val('');
        $('#przychodnia').removeAttr('readonly');
        $('#przychodnia').show();
        $('#przychodnia').removeAttr('disabled');

        $('#przychodnia-poz').attr('disabled', 'disabled');
        $('#przychodnia-poz').hide();
    });

    $('#skad, #dokad').keyup(function(evt){

        // force: true to lower case all letter except first
        var cp_value= ucfirst($(this).val(),true) ;
  
        // to capitalize all words  
        //var cp_value= ucwords($(this).val(),true) ;
  
  
        $(this).val(cp_value);
  
     });
    
});