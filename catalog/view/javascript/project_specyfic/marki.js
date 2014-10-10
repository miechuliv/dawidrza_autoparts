/**
 * Created with JetBrains PhpStorm.
 * User: USER
 * Date: 12.07.13
 * Time: 15:51
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){

    $('#year').change(function(){




            if($('#make option:selected').val()=='Marke')
            {
                   return false;
            }

            var make_id = $('#make option:selected').val();
            var year = $('#year option:selected').val();

            getModel(make_id,year);

        }

    )





$('#make').change(function(){

        var make_id = $('#make option:selected').val();
        var year = $('#year option:selected').val();

        if($('#make option:selected').text()=='Marke' || $('#make option:selected').text()=='')
        {
            $('#model').empty();
            $('#model').append('<option>Modell</option>');
            return false;
        }


        getModel(make_id,year);

    }

)

function getModel(make_id,year)
{
    $.ajax({
        type     : "POST",
        url      : "index.php?route=tool/cars/getModelsAjax",
        dataType: 'json',
        data     : {
            make_id : make_id,
            year : year
        },
        success : function(data) {

            var html='';

            // var obj = jQuery.parseJSON(data);
            if(data['output']==null)
            {
               // alert("Wir konnten leider kein Auto zu Ihren Baujahr und Ihrer  angegebenen Marke finden. Bitte wahelen Sie ein anderes Baujahr oder waehlen Sie einfach alle Baujahre aus! Vielen Dank");

                $('#model').html('<option>Modell</option>');
                $('#type').html('<option>Typ</option>');

            }


            html+='<option value="" >Modell</option>';

            jQuery.each( data['output'] , function(index, value) {

                html+='<option value="'+value['model_id']+'" >'+value['model_name']+'</option>';
            });

            $('#model').html(html);

        },
        complete : function(r) {


        },
        error:    function(error) {


        }
    });
}

$('#model').change(function(){

        var model_id = $('#model option:selected').val();

        if($('#model option:selected').text()=='Modell' || $('#model option:selected').text()=='')
        {
            $('#type').empty();
            $('#type').append('<option>Typ</option>');
            return false;
        }

        getType(model_id);
    }

)

function getType(model_id)
{
    $.ajax({
        type     : "POST",
        url      : "index.php?route=tool/cars/getTypesAjax",
        dataType: 'json',
        data     : {
            model_id : model_id

        },
        success : function(data) {

            var html='';

            if(data['output']==null)
            {
              //  alert("Wir konnten leider kein Auto zu Ihren Baujahr und Ihrer  angegebenen Marke finden. Bitte wahelen Sie ein anderes Baujahr oder waehlen Sie einfach alle Baujahre aus! Vielen Dank");

                $('#type').html('<option>Typ</option>');
            }

            html+='<option value="" >Typ</option>';

            // var obj = jQuery.parseJSON(data);

            jQuery.each( data['output'] , function(index, value) {

                html+='<option value="'+value['type_id']+'" >'+value['type_name']+'</option>';
            });

            $('#type').html(html);

        },
        complete : function(r) {
            //ten fragment wykona się po ZAKONCZENIU połączenia
            //"r" to przykładowa nazwa zmiennej, która zawiera dane zwrócone z serwera

        },
        error:    function(error) {
            //ten fragment wykona się w przypadku BŁĘDU

        }
    });

}



});

function paginationCallback(page)
{
    var currentAction = $("#czesci_szuk").attr("action");
    $("#czesci_szuk").attr("action", currentAction+'&page='+page);
    $("#czesci_szuk").submit();
}

function universalCallback(value,parameter_name)
{
    var currentAction = $("#czesci_szuk").attr("action");
    $("#czesci_szuk").attr("action", currentAction+'&'+parameter_name+'='+value);
    $("#czesci_szuk").submit();
}

function categoryCallback(url)
{

    $("#czesci_szuk").attr("action", url);
    $("#czesci_szuk").submit();
}