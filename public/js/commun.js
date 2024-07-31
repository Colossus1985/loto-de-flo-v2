$(document).ready(function() {
    if ($("#loader")) { $("#loader").hide() }
    if ($("#loader-standalone")) { $("#loader-standalone").hide() }
    
    $(function() {
        $(".tooltip-title").tooltip();
    });
});

$('form').on('submit',function(){
    if ($("#loader")) { $("#loader").show() }
    //if ($("#loader-standalone")) { $("#loader-standalone").show() }
});

function popWin(url,win,para) {
var win = window.open(url,win,para);
win.focus();
}

$('.pop').on('dblclick',function(){
    $(this).hide();
});

function get_selection() {
var txt = '';
if (window.getSelection) {
    txt = window.getSelection();
} else if (document.getSelection) {
    txt = document.getSelection();
} else if (document.selection) {
    txt = document.selection.createRange().text;
}
return $.trim(txt);
}


$('.popup-s').click(function(e){
e.preventDefault();
url = $(this).attr('href');
nom = $(this).attr('data-nom-fenetre');
w = $(this).attr('data-w')===undefined ? 530 : $(this).attr('data-w');
h = $(this).attr('data-h')===undefined ? 680 : $(this).attr('data-h');
window.open(url, nom, "menubar=no, status=no, scrollbars=yes, menubar=no, width="+w+" , height="+h+", left=100, top=1");
});

function AutoRefresh( t ) {
setTimeout("location.reload(true);", t);
}

function escapeHtml(str) {
return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
} 

console.log('ici');
// Datatables valeurs defaut
$.extend( $.fn.dataTable, {
    language: {
        "sProcessing": "Traitement en cours...",
        "sSearch": "Rechercher&nbsp;:",
        "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix": "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable": "Pas de valeur",
        "oPaginate": {
            "sFirst": "Premier",
            "sPrevious": "Pr&eacute;c&eacute;dent",
            "sNext": "Suivant",
            "sLast": "Dernier"
        },
        "oAria": {
            "sSortAscending": ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
        }
    }
    //order: [[0, 'desc']]
} );
