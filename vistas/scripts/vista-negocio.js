var tabla;
$( document ).ready(function() {

    $("#btnExport").on("click",  function(e) {
    	//variables para imprimir
      	var datos_tabla = document.getElementById("sheetjs");
      	var fecha_evento = [ ["Fecha del evento"], [$("#fecha_evento").val()] ];
      	var tipo_evento = [ ["Tipo de evento"], [$("#tipo_evento").val()] ];
      	var nombre_evento = [ ["Nombre(s) del cliente(s)"], [$("#nombre_evento").val()] ];
      	var invitados_evento = [ ["Número de invitados"], [$("#invitados_evento").val()] ];
      	var cotizacion_evento = [ ["Tipo de cotización"], [$("#cotizacion_evento").val()] ];
      	var notas_evento = [ ["Especificaciones adicionales"], [$("#notas_evento").val()] ];
		var nombre = $("#tipo_evento").val() + ": " + $("#fecha_evento").val();    

		var wb = XLSX.utils.book_new();
		wb.Props = {
        	Title: nombre,
        	CreatedDate: new Date()
      	};

      	wb.SheetNames.push("Evento");
      	var ws2 = XLSX.utils.table_to_sheet(datos_tabla, {origin: "B14"});
      	XLSX.utils.sheet_add_aoa(ws2, fecha_evento, {origin: "B2"});
      	XLSX.utils.sheet_add_aoa(ws2, tipo_evento, {origin: "D2"});
     	XLSX.utils.sheet_add_aoa(ws2, nombre_evento, {origin: "B8"});
     	XLSX.utils.sheet_add_aoa(ws2, invitados_evento, {origin: "B8"});
      	XLSX.utils.sheet_add_aoa(ws2, cotizacion_evento, {origin: "D8"});
      	XLSX.utils.sheet_add_aoa(ws2, notas_evento, {origin: "B11"});

      	// wb.Sheets["Evento"] = ws;

      	wb.Sheets["Evento"] = ws2;
      	var wscols = [
			{wch: 2},
			{wch: 25},
			{wch: 25},
			{wch: 15},
			{wch: 15}
		];

		wb.Sheets.Evento['!cols'] = wscols;
      	var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
      	saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), nombre + '.xlsx');
    });
});

function s2ab(s) { 
  	var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
  	var view = new Uint8Array(buf);  //create uint8array as viewer
  	for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
  	return buf;    
}

// Inicio Función de inicio
function init(){
	
	//Llamamos a la función listar
	listar();
	//Al dar clic en el botón guardar llamamos a guardaryeditar con la información en e
	$("#formulario").on("submit",function(e){
		guardaryeditar(e);	
	});

	//Cargamos los items al select proveedor
	$.post("../ajax/evento.php?op=selectCategoria", function(r){
		$("#id_categoria_servicio").html(r);
		$('#id_categoria_servicio').selectpicker('refresh');
	});

	$('#mCompras').addClass("treeview active");
	$('#lIngresos').addClass("active");	
}

 

function listar(){
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdf',
			'pageLength'
		],
		'pageLength': 30,
		language: {
			buttons: {
				pageLength: {
					_: "Mostrar %d filas",
					'-1': "Todos"
				}
			},
      	},
		"columnDefs": [
			{
				targets: 8,
				visible: false,
				searchable: false
			},
		],
		'aLengthMenu': [[ 10, 20, 50, 100 ,-1],[10,20,50,100,"Todos"]],
		"ajax":
		{
			url: '../ajax/vistas.php?op=listarVistaNegocio',
			type : "get",
			dataType : "json",						
			error: function(e){
				console.log(e.responseText);	
			}
		},
		"bDestroy": true,
		// "iDisplayLength": 100,//Paginación
	    "order": [[ 8, "asc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

function listarServicios(){
	tabla=$('#tblarticulos').dataTable({
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [],
		"ajax":{
			url: '../ajax/evento.php?op=listarServicios',
			type : "get",
			dataType : "json",						
			error: function(e){
				console.log(e.responseText);	
				}
			},
		"bDestroy": true,
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}




function activar(id_evento){
	bootbox.confirm("¿Está seguro de regresar a programado el evento?", function(result){
		if(result){
			$.post("../ajax/evento.php?op=activar", {id_evento : id_evento}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
				location.reload();
			});	
		}
	});
}

function anular(id_evento){
	bootbox.confirm("¿Está seguro de finalizar el evento?", function(result){
		if(result){
			$.post("../ajax/evento.php?op=anular", {id_evento : id_evento}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
				location.reload();
			});	
		}
	});
}



function actualizarTablaaExportar(){ //Función para actualizar la tabla que exporta a excel. La invoca el botón de guardar
	// $("#detalles").
	$("#sheetjs > tbody").empty();
	$("#detalles > tbody > tr").each(function () {
		let servicio = $(this).find('td').eq(1).text();
		let categoria = $(this).find('td').eq(2).text();
		let cantidad = parseFloat($(this).find('td').eq(3).find("input").val());
		let precio = parseFloat($(this).find('td').eq(4).find("input").val());
		let subtotal = $(this).find('td').eq(5).text();
		precio = precio.toFixed(2)
		cantidad = cantidad.toFixed(2)
    	console.log(categoria);
    	console.log(cantidad);
    	$("#sheetjs > tbody").append("<tr><td>"+servicio+"</td><td>"+categoria+"</td><td>"+cantidad+"</td><td>"+precio+"<td>"+subtotal+"</td></tr>");
	});

	let total = $("#detalles #total").text();
	$("#sheetjs #total").text(total);
}



//Declaración de variables necesarias para trabajar con las compras y
//sus detalles

var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").show();

function agregarDetalle(id_servicio,nombre_servicio, categoria_servicio){
  	console.log(categoria_servicio)
  	var cantidad_detalle_evento=1;
    var precio_detalle_evento=1;
    cont = document.getElementsByName("subtotal[]").length;
    if (id_servicio!=""){
    	var subtotal=cantidad_detalle_evento*precio_detalle_evento;
    	console.log("subtotal " + subtotal)
    	var fila='<tr class="filas" id="fila'+cont+'">'+
    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    	'<td><input type="hidden" name="id_servicio[]" value="'+id_servicio+'">'+nombre_servicio+'</td>'+
    	'<td>'+categoria_servicio+'</td>'+
    	'<td><input type="number" name="cantidad_detalle_evento[]" id="cantidad_detalle_evento[]" value="'+cantidad_detalle_evento+'"></td>'+
    	'<td><input type="number" name="precio_detalle_evento[]" id="precio_detalle_evento[]" value="'+precio_detalle_evento+'"></td>'+
    	'<td><span name="subtotal[]" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
    	'<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
    	'</tr>';
    	cont++;
    	detalles=detalles+1;
    	$('#detalles').append(fila);
    	modificarSubototales();
    }else{
    	alert("Error al ingresar el detalle, revisar los datos del servicio");
    }
}


init();