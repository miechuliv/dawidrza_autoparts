/**
 * Created with JetBrains PhpStorm.
 * User: USER
 * Date: 19.11.13
 * Time: 15:19
 * To change this template use File | Settings | File Templates.
 */
var route;

function translateSetRoute(r)
{
    route = r;
}

function getActiveControllers()
{
    var inputs = $('input[name="active_controller[]"]');

    var t = [];
    var i = 0;

    $(inputs).each(function(){
        t[i] = $(this).val();
        i++;
    })

    return t;
}

// znajduje zaznaczony przez usera teks//
function getSelectionHtml() {
    var html = "";
    if (typeof window.getSelection != "undefined") {

        var sel = window.getSelection();


        if (sel.rangeCount) {
            var container = document.createElement("div");
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                container.appendChild(sel.getRangeAt(i).cloneContents());
            }
            html = container.innerHTML;
        }
    } else if (typeof document.selection != "undefined") {


        if (document.selection.type == "Text") {
            html = document.selection.createRange().htmlText;
        }
    }
    return html;
}

var searchText;

// reaguje na wcisniety CTRL
function KeyPress(e) {
    var evtobj = window.event? event : e;


    if (evtobj.keyCode == 17)
    {


        var text = getSelectionHtml();

        searchText = text;

        var controllers = getActiveControllers();

        ajaxSearchTranslation(text,controllers);



    }
}

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
        $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
        $(window).scrollLeft()) + "px");
    this.css("z-index", "1000");
    this.css("border", "1px solid black");
    this.css("background-color", "white");
    return this;
}

function killTranslator(elem)
{
    $(elem).parent().empty();
}

function displayTranslateBox(response)
{


    html = '<div class="kill-box" onclick="killTranslator(this)"  >X</div>';


    // dla kazdego znalezionego pliku z tłuamczeniem
    if(response)
    {
        $.each(response,function(key,elem){

            html += '<input type="checkbox" name="controllers[]" value="'+elem["name"]+'" />';
            html += elem["name"]+'<br/>';

        });


    }

    html += '<input type="checkbox" name="saveText" value="1" />';
    html += 'Nie znalazłem właściwego pliku<br/>Zapisz frazę do tłuamczenia w pliku dla programisty<br/>';
    html += 'Tłumaczenie: <input type="text" name="newText"  />';

    html += '<input type="submit" value="Zapisz" onclick="saveTranslation()" />';
    html += '<input type="hidden" name="url" value="'+route+'"  />';



    $("#translateBox").append(html);

    $("#translateBox").center();

}

function saveTranslation()
{

    // stary tekst
    var oldText = searchText;
    // nowy tekst
    var newText = $("#translateBox").find('input[name="newText"]').val();
    // czy zapisać tekst do wstawienia w pliku tekstowym
    var saveText = $("#translateBox").find('input[name="saveText"]').prop('checked');
    // lista plików ktorych ma dotyczyć zmiana
    var controllers = $('#translateBox input[name="controllers[]"]:checked').val();

    var url = $('#translateBox input[name="url"]').val();


    $.ajax({
        url: 'index.php?route=common/translate/save',
        type: 'post',
        data     : {
            oldText : oldText,
            newText: newText,
            saveText: saveText,
            controllers : controllers,
            url: url
        },
        dataType: 'json',
        success: function(json) {
            location.reload();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

        }
    });
}



function ajaxSearchTranslation(text,controllers)
{

    text = text.replace(/(<([^>]+)>)/ig,"");

    $.ajax({
        url: 'index.php?route=common/translate/check',
        type: 'post',
        data     : {
            text : text,
            controllers : controllers
        },
        dataType: 'json',
        success: function(json) {


            displayTranslateBox(json);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

        }
    });

}

$(document).ready(function(){
    document.onkeydown = KeyPress;
});