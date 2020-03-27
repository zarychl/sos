function closeCardConf(cardid)
{
    var result = confirm("Czy napewno chcesz zakończyć tę kartę ?");
    if (result) {
        location.href = "przejazdyZamknij.php?idkarty="+cardid;
    }
}
function deleteEventConf(id)
{
    var result = confirm("Czy napewno chcesz usunąć to wydarzenie ?");
    if (result) {
        location.href = "terminarzDelete.php?id="+id;
    }
}

$(document).ready(function($) {
    $(document).on('click',".clickable-row", function() {
        window.document.location = $(this).data("href");
    });
    $(document).on('click',"#terminarzGlowna", function() {
        var today = new Date();
        var month = today.getMonth()*1;
        month++;
        if(month < 10)
            month = "0" + month;
        window.document.location = "terminarzDay.php?date=" + today.getFullYear() + "-" + month + "-" +  today.getDate();
        
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
    if($('#pacjentCheck').prop("checked") == true){
        $('#pacjentName').show();
        $('#pacjentName').removeAttr('disabled');
    }
    $('#pacjentCheck').click(function(){
        if($(this).prop("checked") == true){
            $('#pacjentName').show();
            $('#pacjentName').removeAttr('disabled');
        }

        else if($(this).prop("checked") == false){
            $('#pacjentName').hide();
            $('#pacjentName').attr('disabled','disabled');
        }
    });

    $('#skad, #dokad').keyup(function(evt){
        var txt = $(this).val();
        $(this).val(txt.replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase( ); }));
  
     });
    
});