/*INICIO: BUSQUEDA DE PROVEEDORES*/
$("#txtbuscarnombre").autocomplete
({
    source: "../controlador/cliente.autocompletar.controlador.php",
    minLength: 2, //Filtrar desde que colocamos 2 o mas caracteres
    focus: f_marcar_registro,
    select: f_seleccionar_registro
});

function f_marcar_registro(event, ui)
{
    var registro = ui.item.value;
    $("#txtbuscarnombre").val(registro.nc);
    event.preventDefault();
}

function f_seleccionar_registro(event, ui)
{
    var registro = ui.item.value;
    $("#txtdni").val(registro.dni);
    $("#lblnombrecliente").val(registro.nc);
    $("#lbldireccioncliente").val(registro.dir);
    $("#lbltelefonocliente").val(registro.telf);
    obtenerPrestamos();
    event.preventDefault();
}
/*FIN: BUSQUEDA DE PROVEEDORES*/


/*INICIO: BUSQUEDA DE ARTICULOS*/
$("#txtarticulo").autocomplete({
    source: "../controlador/articulo.autocompletar.controlador.php",
    minLength: 2, //Filtrar desde que colocamos 2 o mas caracteres
    focus: f_marcar_registro_articulo,
    select: f_seleccionar_registro_articulo
});

function f_marcar_registro_articulo(event, ui){
    var registro = ui.item.value;
    $("#txtarticulo").val(registro.nombre);
    event.preventDefault();
}

function f_seleccionar_registro_articulo(event, ui){
    var registro = ui.item.value;
    $("#txtarticulo").val(registro.nombre);
    $("#txtprecio").val(registro.preciov);
    $("#txtcodigoarticulo").val(registro.codigo); //campo oculto (hidden)
    $("#txtcantidad").focus();
    
    event.preventDefault();
}
/*FIN: BUSQUEDA DE ARTICULOS*/


