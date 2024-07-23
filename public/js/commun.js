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
