$("#txtdni").change(function ()
{
    obtenerCliente();
    obtenerPrestamos();
});


$("#rbtipo1").click(function()
{
    buscarAcciones();
});
$("#rbtipo2").click(function()
{
    buscarAcciones();
});

function buscarAcciones()
{
    var test = ($("#rbtipo1").is(":checked"))?$("#rbtipo1").val():$("#rbtipo2").val();
    if (test=="1")
    {
        $("#txtdni").attr("readonly", false);
        $("#txtbuscarnombre").attr("readonly", true);
        $("#txtbuscarnombre").val("");
        $("#txtdni").val("");
        $("#txtdni").change();
    }
    else
    {
        $("#txtbuscarnombre").attr("readonly", false);
        $("#txtdni").attr("readonly", true);
        $("#txtdni").val("");
        $("#txtdni").change();
    }
}


$("#cboprestamos").change(function ()
{
    var id = $("#cboprestamos").val();

    if (id=="")
    {
        $("#detalleprestamo").empty();
        return;
    }

    $.post(
        "../controlador/pago.controlador.php",
        {
            p_id: id
        }
    ).done(function(resultado)
        {
            $("#detalleprestamo").empty();
            $("#detalleprestamo").append(resultado);
        });
});

function obtenerCliente()
{
    var dni = $("#txtdni").val();
    $.post(
        "../controlador/pago.cliente.controlador.php",
        {
            p_dni: dni
        }
    ).done(function(resultado)
        {
            $("#detalleprestamo").empty();
            var datos = $.parseJSON(resultado);
            $("#lblnombrecliente").val(datos.nombrecliente);
            $("#lbltelefonocliente").val(datos.telefono);
            $("#lbldireccioncliente").val(datos.direccion);
        });
}

function obtenerPrestamos()
{
    var dni = $("#txtdni").val();
    $.post(
        "../controlador/pago.prestamo.controlador.php",
        {
            p_dni: dni
        }
    ).done(function(resultado)
        {
            $("#cboprestamos").empty();
            $("#cboprestamos").append(resultado);
            calcularTotales();

        });
}

$(document).ready(function()
{
    buscarAcciones();
});


$("#txtcantidad").keypress(function(evento)
{
    if (evento.which === 13){
        evento.preventDefault(); //ignore el evento
        $("#btnagregar").click();
    }
});

$(document).on("dblclick", "#cmontopagar", function()
{
   var montopagar = $(this).html();
   
   if (montopagar.substring(0,6)==="<input")
   {
       return 0;
   }
   
   $(this).empty().append('<input type="text" id="txtactualizar" class="form-control" value = "' + montopagar + '"/>');
   $("#txtactualizar").focus();
   
});

$(document).on("keypress", "#txtactualizar", function(evento)
{
    
    if (evento.which === 13)
    {
        var saldo = $(this).parents().find("td").eq(3).html();
        var monto = $(this).val();
        if (parseFloat(monto)>parseFloat(saldo))
        {
            alert("El importe pagado no puede ser mayor que el saldo.");
            monto = 0;
            $(this).parents().find("td").eq(4).empty().append(monto);
        }
        else if(monto=="")
        {
            monto = 0;
            $(this).parents().find("td").eq(4).empty().append(monto);
        }
        else
        {
            $(this).parents().find("td").eq(4).empty().append(monto);

        }

        calcularTotales();
    }
    else
    {
        return validarNumeros(evento);
    }
});


function calcularTotales()
{
    var importeNeto=0;

    $("#detallecuotas tr").each(function()
    {
        var importe = $(this).find("td").eq(4).html();
        importeNeto = importeNeto + parseFloat(importe);
    });
    $("#txttotalpagado").val(importeNeto.toFixed(2));
}




var arrayDetalle = new Array();

$("#frmgrabar").submit(function(evento)
 {
   evento.preventDefault();
   
    /*limpiar el array*/
    arrayDetalle.splice(0, arrayDetalle.length);
    /*limpiar el array*/
   
   /*CAPTURAR LOS DATOS PARA EL DETALLE DE COMPRA*/
   var item=0;
   $("#detallecuotas tr").each(function()
   {
       var numerocuota = $(this).find("td").eq(0).html();
       var importepagado = $(this).find("td").eq(4).html();

       item = item + 1;
       
       var objDetalle = new Object();
       objDetalle.numerocuota = numerocuota;
       objDetalle.item = item;
       objDetalle.importepagado  = importepagado;
       arrayDetalle.push(objDetalle);
       
   });
   
   var jsonDetalle = JSON.stringify(arrayDetalle);
   
   //alert(jsonDetalle);
   /*CAPTURAR LOS DATOS PARA EL DETALLE DE COMPRA*/
   
   
   $.post(
           "../controlador/pago.agregar.controlador.php",
           {
               p_array_datos_cabecera: $("#frmgrabar").serialize(),
               p_json_datos_detalle: jsonDetalle
           }
        ).done(function(resultado)
       {
            if ($.trim(resultado)==="exito")
            {
                document.location.href="pago-listado.php";
            }
           else
            {
                alert(resultado);
            }
           //alert(resultado);
           
        }).fail(function(error){
            alert("Error:" + error.responseText);
        });
        
   
});