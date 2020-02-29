// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable( {
      "oPaginate": {
        "sNext": "Następna",
        "sPrevious": "Poprzednia"
      },
      "oLanguage": {
        "sSearch": "Wyszukaj: _INPUT_",
        "sLengthMenu": "Pokaż _MENU_ wpisów",
        "sInfo": "Wyświetlono _START_ do _END_ z _TOTAL_ wpisów."
      }
  } );
});
