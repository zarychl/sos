function closeCardConf(cardid)
{
    var result = confirm("Czy napewno chcesz zakończyć tę kartę ?");
    if (result) {
        location.href = "przejazdyZamknij.php?idkarty="+cardid;
    }
}