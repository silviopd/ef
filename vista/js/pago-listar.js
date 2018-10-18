function listar(){
    var tipo = $("#rbtipo:checked").val();
    var fecha1 = $("#txtfecha1").val();
    var fecha2 = $("#txtfecha2").val();
    
    $.post(
            "../controlador/pago.listar.controlador.php",
            {
                p_fecha1: fecha1,
                p_fecha2: fecha2,
                p_tipo: tipo
            }
            ).done(function(resultado){
                $("#listado").empty();
                $("#listado").append(resultado);
                $('#tabla-listado').dataTable({
                    "aaSorting": [[1, "desc"]],
                    
                    "sScrollX":       "100%",
                    "sScrollXInner":  "150%",
                    "bScrollCollapse": true,
                    "bPaginate":       true 
                });
            })
}

$(document).ready(function(){
    listar(); 
});

$("#btnfiltrar").click(function(){
    listar(); 
});

function anular(numeroPagoAnular)
{
    if (! confirm("Esta seguro de anular el pago seleccionado")){
        return 0;
    }
    
    $.post
            (
                    "../controlador/pago.anular.controlador.php",
                    {
                        p_nro_pago: numeroPagoAnular
                    }
            ).done(function (resultado)
        {
                if ($.trim(resultado) === "exito")
                {
                    listar();
                }
            else
                {
                    alert(resultado);
                }
            }).fail(function(error)
        {
                alert(error.responseText);
            })
    
}

$("#btnagregar").click(function(){
   document.location.href="pago.php";
});