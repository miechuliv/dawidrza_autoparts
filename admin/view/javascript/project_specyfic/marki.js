/**
 * Created with JetBrains PhpStorm.
 * User: USER
 * Date: 12.07.13
 * Time: 15:51
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){

   /* $('#cars_make_filter').change(function(){




            if($('#cars_make_filter option:selected').val()=='Marka')
            {
                   return false;
            }

            var make_id = $('#cars_make_filter option:selected').val();
            var year = false

            getModel(make_id,year);

        }

    ) */





$('#cars_make_filter').change(function(){

        var make_id = $('#cars_make_filter option:selected').val();
        var year = false;

        if($('#cars_make_filter option:selected').text()=='Marka' || $('#cars_make_filter option:selected').text()=='')
        {
            $('#cars_model_filter').empty();
            $('#cars_model_filter').append('<option>Model</option>');
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
            year : year,
            pass: 'ajax'
        },
        success : function(data) {

            var html='';

            // var obj = jQuery.parseJSON(data);
            if(data['output']==null)
            {
               // alert("Wir konnten leider kein Auto zu Ihren Baujahr und Ihrer  angegebenen Marke finden. Bitte wahelen Sie ein anderes Baujahr oder waehlen Sie einfach alle Baujahre aus! Vielen Dank");

                $('#cars_make_filter').html('<option>Model</option>');
                $('#cars_type_filter').html('<option>Typ</option>');

            }


            html+='<option value="" >Model</option>';

            jQuery.each( data['output'] , function(index, value) {

                html+='<option value="'+value['model_id']+'" >'+value['model_name']+'</option>';
            });

            $('#cars_model_filter').html(html);

        },
        complete : function(r) {


        },
        error:    function(error) {


        }
    });
}

$('#cars_model_filter').change(function(){

        var model_id = $('#cars_model_filter option:selected').val();

        if($('#cars_model_filter option:selected').text()=='Model' || $('#cars_model_filter option:selected').text()=='')
        {
            $('#cars_type_filter').empty();
            $('#cars_type_filter').append('<option>Typ</option>');
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
            model_id : model_id,
            pass: 'ajax'

        },
        success : function(data) {

            var html='';

            if(data['output']==null)
            {
              //  alert("Wir konnten leider kein Auto zu Ihren Baujahr und Ihrer  angegebenen Marke finden. Bitte wahelen Sie ein anderes Baujahr oder waehlen Sie einfach alle Baujahre aus! Vielen Dank");

                $('#cars_type_filter').html('<option>Typ</option>');
            }

            html+='<option value="" >Typ</option>';

            // var obj = jQuery.parseJSON(data);

            jQuery.each( data['output'] , function(index, value) {

                html+='<option value="'+value['type_id']+'" >'+value['type_name']+'</option>';
            });

            $('#cars_type_filter').html(html);

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

