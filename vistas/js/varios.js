$(function(){
	// Crear la fila y la agrega al final de la tabla
	$("#adicional").on('click', function(){
		var fila = '<tr class="fila-fija">'+
					'<td>'+
						'<input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" placeholder="Embase*" name="presentacion_embase_reg[]" maxlength="10"required>'+
					'</td>'+
					'<td>'+
						'<input type="number" min="1" max="1000" class="form-control" placeholder="Contenido*" name="presentacion_contenido_reg[]" maxlength="4" required>'+
					'</td>'+
					'<td>'+
						'<select class="form-control" name="presentacion_medida_reg[]">'+
							'<option value="" selected="" disabled="">Seleccione una medida*</option>'+
							'<option value="mg.">Miligramos</option>'+
							'<option value="g.">Gramos</option>'+
							'<option value="Kg.">Kilogramos</option>'+
							'<option value="ml.">Mililitros</option>'+
							'<option value="l.">Litros</option>'+
						'</select>'+
					'</td>'+
					'<td>'+
						'<input type="number" min="0" max="1000" step="0.01" class="form-control" placeholder="Precio Venta Und.*" name="presentacion_preciov_reg[]" maxlength="4" required>'+
					'</td>'+
					'<td class="eliminar" width="10%">'+
						'<button type="button" class="btn btn-raised btn-danger btn-lgs btn-block"><i class="fas fa-minus"></i></button>'+
					'</td>'+
				'</tr>';
		$("#tabla").append(fila);
	});
	

	$("#adicional_up").on('click', function(){
		var fila = '<tr class="fila-fija">'+
					'<td>'+
						'<input type="text" pattern="[a-zA-Z0-9-]{1,10}" class="form-control" placeholder="Embase*" name="presentacion_embase_up[]" maxlength="10"required>'+
					'</td>'+
					'<td>'+
						'<input type="number" min="1" max="1000" class="form-control" placeholder="Contenido*" name="presentacion_contenido_up[]" maxlength="4" required>'+
					'</td>'+
					'<td>'+
						'<select class="form-control" name="presentacion_medida_up[]">'+
							'<option value="" selected="" disabled="">Seleccione una medida*</option>'+
							'<option value="mg.">Miligramos</option>'+
							'<option value="g.">Gramos</option>'+
							'<option value="Kg.">Kilogramos</option>'+
							'<option value="ml.">Mililitros</option>'+
							'<option value="l.">Litros</option>'+
						'</select>'+
					'</td>'+
					'<td>'+
						'<input type="number" min="0" max="1000" step="0.01" class="form-control" placeholder="Precio Venta Und.*" name="presentacion_preciov_up[]" maxlength="4" required>'+
					'</td>'+
					'<td class="eliminar" width="10%">'+
						'<button type="button" class="btn btn-raised btn-danger btn-lgs btn-block"><i class="fas fa-minus"></i></button>'+
					'</td>'+
				'</tr>';
		$("#tabla").append(fila);
	});
	

	// Evento que selecciona la fila y la elimina 
	$(document).on("click",".eliminar",function(){
		var table = document.getElementById("tabla");
		var tbodyRowCount = table.tBodies[0].rows.length;
		if(tbodyRowCount == 2){
			$(".eliminar").css('display','none');
		}
		if(tbodyRowCount > 1){
			var parent = $(this).parents().get(0);
			$(parent).remove();
		}else{
			Swal.fire({
				type: 'error',
				title: 'Ocurrió un error inesperado',
				text: 'Debe ingresar al menos una presentación de la medicina.',
				confirmButtonText: 'Aceptar',
			});
		}
	});
});

var precioTotal = 0;
var cantidadTotal = 0;

function agregar_lote(id){
	$('#ModalLote .modal-body').load('../../controladores/modalControlador.php?op=agregarLote&id='+id,function(){
		$('#ModalLote').modal({show:true});
	});
}
function listar_lote(id){
	$('#ModalListaLote .modal-body').load('../../controladores/modalControlador.php?op=listarLote&id='+id,function(){
		$('#ModalListaLote').modal({show:true});
	});
}
function editar_lote(idLote, id){
	$('#ModalEditarLote .modal-body').load('../../controladores/modalControlador.php?op=editarLote&id='+id+'&idlote='+idLote,function(){
		$('#ModalEditarLote').modal({show:true});
	});
}

/** Cliente ventas*/
function buscar_cliente() {
	cliente = document.getElementById("input_cliente").value;
	if (cliente == "") {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'Debe ingresar un dato para poder realizar la busqueda',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	$.ajax({
		type: "POST",
		url: "../../controladores/modalControlador.php",
		data: { "cliente" :  cliente, "op" : "buscarCliente" },
		success: function(data){
			document.getElementById('contenido_busqueda').innerHTML = data;
		}
	});
}

function añadir_cliente(id, nombre) {
	var inputId = document.getElementById("id_cliente_seleccionado");
	inputId.value = id;
	nombreHtml = '<span class="roboto-medium">CLIENTE: </span><div class="form-group" style="display: inline-block !important;">' + nombre + '<button type="button" class="btn btn-danger" onclick="quitar_cliente()"><i class="fas fa-user-times"></i></button></div>';
	document.getElementById('cliente_seleccionado').innerHTML = nombreHtml;
}

function quitar_cliente() {
	var inputId = document.getElementById("id_cliente_seleccionado");
	inputId.vaalue = "";
	Html = '<span class="roboto-medium">CLIENTE:</span> <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un cliente</span><br><br>';
	document.getElementById('id_cliente_seleccionado').innerHTML = Html;
}


/** Item ventas */
var stockactual = 0;
function buscar_item() {
	item = document.getElementById("input_item").value;
	if (item == "") {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'Debe ingresar un dato para poder realizar la busqueda',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	$.ajax({
		type: "POST",
		url: "../../controladores/modalControlador.php",
		data: { "item" :  item, "op" : "buscarItem" },
		success: function(data){
			document.getElementById('contenido_busqueda_item').innerHTML = data;
		}
	});
}


function add_item(id, nombre, precio, stock) {
	stockactual = stock
	$.ajax({
		type: "POST",
		url: "../../controladores/modalControlador.php",
		data: { "id" :  id,"nombre" : nombre, "precio" : precio, "stock" : stock, "op" : "agregarItem" },
		success: function(data){
			document.getElementById('modal-body').innerHTML = data;
			$('#ModalAgregarItem').modal({show:true});
		}
	});
}

function agregar_item() {
	if (stockactual < parseInt(document.getElementById("detalle_cantidad").value)) {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'No puede ingresar una cantidad mayor al stock registrado',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	if (parseInt(document.getElementById("detalle_cantidad").value) < 1) {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'La cantidad ingresada debe ser como mínimo 1',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	if (document.getElementById("detalle_cantidad").value == "") {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'Debe ingresar un valor en la cantidad',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	if( !(/^([0-9])*$/.test(document.getElementById("detalle_cantidad").value)) ) {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'La cantidad ingresada no coincide con el formato correcto',
            confirmButtonText: 'Aceptar'
		});
		return
	  }
	$('#ModalAgregarItem').modal('hide');
	id = document.getElementById("id_agregar_item").value;
	nombre = document.getElementById("nombre_agregar_item").value;
	precio = document.getElementById("precio_agregar_item").value;
	cantidad = document.getElementById("detalle_cantidad").value;
	cantidadTotal = cantidadTotal + parseInt(cantidad);
	precioTotal = precioTotal + (parseFloat(precio) * parseInt(cantidad));
	var fila = '<tr class="text-center" >'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="item_reg[]" value="'+ id +'"></td>'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="cantidad_reg[]" value="'+ cantidad +'"></td>'+
					'<td class="numero">' + nombre + '</td>'+
					'<td class="numero">' + cantidad + '</td>'+
					'<td class="numero">N/A</td>'+
					'<td class="numero">' + precio + ' Bs.</td>'+
					'<td class="numero">' + precio*cantidad + ' Bs.</td>'+
					'<td class="eliminar_fila">'+
						'<button type="button" class="btn btn-warning"><i class="far fa-trash-alt"></i></button>'+
					'</td>'+
				'</tr>'+
				'<tr class="text-center bg-light totales">'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="canttotal_venta_reg" value="'+ cantidadTotal +'"></td>'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="prectotal_venta_reg" value="'+ precioTotal +'"></td>'+
					'<td><strong>TOTAL</strong></td>'+
					'<td><strong>' + cantidadTotal + ' items</strong></td>'+
					'<td colspan="2"></td>'+
					'<td><strong>' + precioTotal + ' Bs.</strong></td>'+
					'<td colspan="2"></td>'+
				'</tr>';
	$(".totales").remove();
	$("#tabla_venta").append(fila);
}

$(document).on("click",".eliminar_fila",function(){
	var valores = "";
	$(this).parents("tr").find(".numero").each(function() {
		valores += $(this).html() + "|";
	  });
	var array = valores.split("|");
	cantidadTotal = cantidadTotal - parseInt(array[1]);
	precioTotal = precioTotal - parseFloat(array[4].substring(0, array[4].length - 4))

	var parent = $(this).parents().get(0);
	$(parent).remove();
	$(".totales").remove();
	var fila = '<tr class="text-center bg-light totales">'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="canttotal_venta_reg" value="'+ cantidadTotal +'"></td>'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="prectotal_venta_reg" value="'+ precioTotal +'"></td>'+
					'<td><strong>TOTAL</strong></td>'+
					'<td><strong>' + cantidadTotal + ' items</strong></td>'+
					'<td colspan="2"></td>'+
					'<td><strong>' + precioTotal + ' Bs.</strong></td>'+
					'<td colspan="2"></td>'+
				'</tr>';
	$("#tabla_venta").append(fila);
});

/** Servicio ventas */
function buscar_servicio() {
	servicio = document.getElementById("input_servicio").value;
	if (servicio == "") {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'Debe ingresar un dato para poder realizar la busqueda',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	$.ajax({
		type: "POST",
		url: "../../controladores/modalControlador.php",
		data: { "servicio" :  servicio, "op" : "buscarServicio" },
		success: function(data){
			document.getElementById('contenido_busqueda_servicio').innerHTML = data;
		}
	});
}

function add_servicio(id, nombre, precio) {
	cantidadTotal = cantidadTotal + 1;
	precioTotal = precioTotal + parseFloat(precio);
	var fila = '<tr class="text-center" >'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="servicio_reg[]" value="'+ id +'"></td>'+
					'<td class="numero">' + nombre + '</td>'+
					'<td class="numero">1</td>'+
					'<td class="numero">N/A</td>'+
					'<td class="numero">' + precio + ' Bs.</td>'+
					'<td class="numero">' + precio + ' Bs.</td>'+
					'<td class="eliminar_fila">'+
						'<button type="button" class="btn btn-warning"><i class="far fa-trash-alt"></i></button>'+
					'</td>'+
				'</tr>'+
				'<tr class="text-center bg-light totales">'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="canttotal_venta_reg" value="'+ cantidadTotal +'"></td>'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="prectotal_venta_reg" value="'+ precioTotal +'"></td>'+
					'<td><strong>TOTAL</strong></td>'+
					'<td><strong>' + cantidadTotal + ' items</strong></td>'+
					'<td colspan="2"></td>'+
					'<td><strong>' + precioTotal + ' Bs.</strong></td>'+
					'<td colspan="2"></td>'+
				'</tr>';
	$(".totales").remove();
	$("#tabla_venta").append(fila);
}

/** Medicinas venta */
function buscar_medicina() {
	medicina = document.getElementById("input_medicina").value;
	if (medicina == "") {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'Debe ingresar un dato para poder realizar la busqueda',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	$.ajax({
		type: "POST",
		url: "../../controladores/modalControlador.php",
		data: { "medicina" :  medicina, "op" : "buscarMedicina" },
		success: function(data){
			document.getElementById('contenido_busqueda_medicina').innerHTML = data;
		}
	});
}

function add_medicina(id, nombre, precio, idPre, fecha) {
	$.ajax({
		type: "POST",
		url: "../../controladores/modalControlador.php",
		data: { "id" :  id,"nombre" : nombre, "precio" : precio, "idPre" : idPre, "fecha" : fecha, "op" : "agregarMedicina" },
		success: function(data){
			document.getElementById('modal-body-medicina').innerHTML = data;
			$('#ModalAgregarMedicina').modal({show:true});
		}
	});
}
function actualizar(valor)
{
	$.ajax({
		type: "POST",
		url: "../../controladores/modalControlador.php",
		data: { "id" :  valor.value, "op" : "agregarStock" },
		success: function(data){
			var array = data.split("|");
			document.getElementById('Stock').innerHTML = array[0];
			stockactual = parseInt(array[1]);
			var inputId = document.getElementById("fecha_agregar_medicina");
			inputId.value = array[2];
		}
	});
}

function agregar_medicina() {
	if (stockactual < parseInt(document.getElementById("detalle_cantidad_medicina").value)) {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'No puede ingresar una cantidad mayor al stock registrado',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	if (parseInt(document.getElementById("detalle_cantidad_medicina").value) < 1) {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'La cantidad ingresada debe ser como mínimo 1',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	if (document.getElementById("detalle_cantidad_medicina").value == "") {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'Debe ingresar un valor en la cantidad',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	if( !(/^([0-9])*$/.test(document.getElementById("detalle_cantidad_medicina").value)) ) {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'La cantidad ingresada no coincide con el formato correcto',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	if (document.getElementById("detalle_cantidad_medicina").value == "") {
		Swal.fire({
            type: 'error',
            title: 'Ocurrio un error',
            text: 'Seleccione una fecha de la lista.',
            confirmButtonText: 'Aceptar'
		});
		return
	}
	$('#ModalAgregarMedicina').modal('hide');
	id = document.getElementById("id_agregar_medicina").value;
	idL = document.getElementById("lote").value;
	nombre = document.getElementById("nombre_agregar_medicina").value;
	precio = document.getElementById("precio_agregar_medicina").value;
	cantidad = document.getElementById("detalle_cantidad_medicina").value;
	fecha = document.getElementById("fecha_agregar_medicina").value;
	cantidadTotal = cantidadTotal + parseInt(cantidad);
	precioTotal = precioTotal + (parseFloat(precio) * parseInt(cantidad));
	var fila = '<tr class="text-center" >'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="medicina_reg[]" value="'+ id +'"></td>'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="medicina_lote_reg[]" value="'+ idL +'"></td>'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="cantidad_medicina_reg[]" value="'+ cantidad +'"></td>'+
					'<td class="numero">' + nombre + '</td>'+
					'<td class="numero">' + cantidad + '</td>'+
					'<td class="numero">' + fecha + '</td>'+
					'<td class="numero">' + precio + ' Bs.</td>'+
					'<td class="numero">' + precio*cantidad + ' Bs.</td>'+
					'<td class="eliminar_fila">'+
						'<button type="button" class="btn btn-warning"><i class="far fa-trash-alt"></i></button>'+
					'</td>'+
				'</tr>'+
				'<tr class="text-center bg-light totales">'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="canttotal_venta_reg" value="'+ cantidadTotal +'"></td>'+
					'<td style="display:none;"><input type="hidden" class="form-control" name="prectotal_venta_reg" value="'+ precioTotal +'"></td>'+
					'<td><strong>TOTAL</strong></td>'+
					'<td><strong>' + cantidadTotal + ' items</strong></td>'+
					'<td colspan="2"></td>'+
					'<td><strong>' + precioTotal + ' Bs.</strong></td>'+
					'<td colspan="2"></td>'+
				'</tr>';
	$(".totales").remove();
	$("#tabla_venta").append(fila);
}

/** Lista de detalle de venta */
function mostrar_detalle(idVenta) {
	$.ajax({
		type: "POST",
		url: "../../controladores/modalControlador.php",
		data: { "id" :  idVenta, "op" : "listarDetalle" },
		success: function(data){
			document.getElementById('modal-body').innerHTML = data;
			$('#ModalDetalle').modal({show:true});
		}
	});
}
