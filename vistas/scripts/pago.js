var tabla;



// Inicio Función de inicio

function init()

{

	//Ocultamos el formulario

	mostrarform(false);

	

	//Llamamos a la función listar

	listar();

	listarTablaAExportar();

	

	//Al dar clic en el botón guardar llamamos a guardaryeditar con la información en e

	$("#formulario").on("submit",function(e)

	{

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





/*

Columnas de la tabla 'cobros'

id_cobro int(11)

id_evento int(11)

fecha_cobro datetime(6)

monto_cobro decimal(15,2)

metodo_cobro varchar(45)

email_cobro varchar(200)



*/



function limpiar()

{

	$("#id_evento").val("");

	$("#fecha_cobro").val("");

	$("#monto_cobro").val("");

	$("#metodo_cobro").val("");

	$("#email_cobro").val("");

}



function mostrarform(flag)

{

	//limpiar();

	if(flag)

	{

		$("#listadoregistros").hide();

		$("#formularioregistros").show();

		$("#btnagregar").hide();

		$("#boton_verde_agregar").hide();

		listarServicios();

		$("#guardar").hide();

		$("#btnGuardar").show();

		$("#btnCancelar").show();

		detalles=0;

		$("#btnAgregarArt").show();

		$("#abrirModal").show();

		



	}

	else

	{

		$("#listadoregistros").show();

		$("#formularioregistros").hide();

		$("#btnagregar").show();

		$("#boton_verde_agregar").show();

	}

}



function cancelarform()

{

	limpiar();

	mostrarform(false);

}



function listar()

{



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

		language: {

	      buttons: {

	        pageLength: {

	   	    _: "Mostrar %d filas",

	       '-1': "Todos"

	      }

	    },

	   },

	   'pageLength': 30,

		'aLengthMenu': [[ 10, 20, 50, 100 ,-1],[10,20,50,100,"Todos"]],

		"ajax":

				{

					url: '../ajax/pago.php?op=listar',

					type : "get",

					dataType : "json",						

					error: function(e){

						console.log(e.responseText);	

					}

				},

		"bDestroy": true,

		"iDisplayLength": 100,//Paginación

	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)

	}).DataTable();

}



function listarTablaAExportar()

{



	tabla=$('#asd').dataTable(

	{

		"aProcessing": true,//Activamos el procesamiento del datatables

	    "aServerSide": true,//Paginación y filtrado realizados por el servidor

	    dom: 'Bfrtip',//Definimos los elementos del control de tabla

		

	    buttons: [		          

		            'copyHtml5',

		            'excelHtml5',

		            'csvHtml5',

		            'pdf'

		        ],

		"ajax":

				{

					url: '../ajax/cobro.php?op=listar',

					type : "get",

					dataType : "json",						

					error: function(e){

						console.log(e.responseText);	

					}

				},

		"bDestroy": true,

		"iDisplayLength": 100,//Paginación

	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)

	}).DataTable();

}



function listarServicios()

{

	tabla=$('#tblarticulos').dataTable(

	{

		"aProcessing": true,//Activamos el procesamiento del datatables

	    "aServerSide": true,//Paginación y filtrado realizados por el servidor

	    dom: 'Bfrtip',//Definimos los elementos del control de tabla

	    buttons: [		          

		           

		        ],

		"ajax":

				{

					url: '../ajax/evento.php?op=listarServicios',

					type : "get",

					dataType : "json",						

					error: function(e){

						console.log(e.responseText);	

					}

				},

		"bDestroy": true,

		"iDisplayLength": 10,//Paginación

	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)

	}).DataTable();

}



function guardaryeditar(e)

{

	modificarSubototales();

	

	e.preventDefault(); //No se activará la acción predeterminada del evento

	//$("#btnGuardar").prop("disabled",true);

	var formData = new FormData($("#formulario")[0]);



	$.ajax({

		url: "../ajax/evento.php?op=guardaryeditar",

	    type: "POST",

	    data: formData,

	    contentType: false,

	    processData: false,



	    success: function(datos)

	    {                    

	          bootbox.alert(datos);	          

	          mostrarform(false);

	          listar();

	    }



	});

	limpiar();

}



function mostrar(id_evento)

{


		mostrarform(true);

		$("#btnGuardar").show();

		$("#abrirModal").show();

	 // $.post("../ajax/cobro.php?op=listarDetalle&id="+id_evento,function(r)

	 // {

		// 	// $("#detalles").html(r);

		// 	$("#detalles").html(r);

	 // });



	 tabla=$('#detalles').dataTable(

		{

			"aProcessing": true,//Activamos el procesamiento del datatables

	    "aServerSide": true,//Paginación y filtrado realizados por el servidor

	    dom: 'Bfrtip',//Definimos los elementos del control de tabla

	    buttons: [		          

        'copyHtml5',

        'excelHtml5',

        'csvHtml5',

        'pdf'

      ],

			"ajax":

				{

					url: "../ajax/pago.php?op=listarDetalle&id="+id_evento,

					type : "get",

					dataType : "json",						

					error: function(e){

						console.log(e.responseText);	

					}

				},

			"bDestroy": true,

			"iDisplayLength": 10,//Paginación

		    "order": [[ 0, "desc" ]], //Ordenar (columna,orden)

			"initComplete": function( settings, json ) {
				if (json['iTotalDisplayRecords'] > 0){
					let tamanio = json['aaData'].length - 1;
					let datos = json['aaData'][tamanio];
					let total = datos[7];
					$("#total").text(total)
				}
			 }

		}).DataTable();



}



function activar(id_evento)

{

	bootbox.confirm("¿Está seguro de regresar a programado el evento?", function(result){

		if(result)

        {

        	$.post("../ajax/evento.php?op=activar", {id_evento : id_evento}, function(e){

        		bootbox.alert(e);

	            tabla.ajax.reload();

				location.reload();

        	});	

        }

	})

}



function anular(id_evento)

{

	bootbox.confirm("¿Está seguro de finalizar el evento?", function(result){

		if(result)

        {

        	$.post("../ajax/evento.php?op=anular", {id_evento : id_evento}, function(e){

        		bootbox.alert(e);

	            tabla.ajax.reload();

				location.reload();

        	});	

        }

	})

}



//Declaración de variables necesarias para trabajar con las compras y

//sus detalles

var cont=0;

var detalles=0;

//$("#guardar").hide();

$("#btnGuardar").show();



function agregarDetalle(id_servicio,nombre_servicio)

  {

  	var cantidad_detalle_evento=1;

    var precio_detalle_evento=1;

    

    if (id_servicio!="")

    {

    	var subtotal=cantidad_detalle_evento*precio_detalle_evento;

    	var fila='<tr class="filas" id="fila'+cont+'">'+

    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+

    	'<td><input type="hidden" name="id_cobro[]" value="'+id_cobro+'">'+fecha_cobro+'</td>'+

    	'<td><input type="number" name="metodo_cobro[]" id="metodo_cobro[]" value="'+metodo_cobro+'"></td>'+

    	'<td><input type="email" name="monto_cobro[]" id="monto_cobro[]" value="'+monto_cobro+'"></td>'+

    	'<td><input type="email" name="email_cobro[]" id="email_cobro[]" value="'+email_cobro+'"></td>'+

    	'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+

    	'<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+

    	'</tr>';

    	cont++;

    	detalles=detalles+1;

    	$('#detalles').append(fila);

    	modificarSubototales();

    }

    else

    {

    	alert("Error al ingresar el detalle, revisar los datos del servicio");

    }

  }



  function modificarSubototales()

  {

  	var cant = document.getElementsByName("cantidad_detalle_evento[]");

    var prec = document.getElementsByName("precio_detalle_evento[]");

    var sub = document.getElementsByName("subtotal");



    for (var i = 0; i <cant.length; i++) {

    	var inpC=cant[i];

    	var inpP=prec[i];

    	var inpS=sub[i];



    	inpS.value=inpC.value * inpP.value;

    	document.getElementsByName("subtotal")[i].innerHTML = inpS.value;

    }

    calcularTotales();



  }





  function calcularTotales(){

  	var sub = document.getElementsByName("subtotal");

  	var total = 0.0;

	

  	for (var i = 0; i <sub.length; i++) {

		total += document.getElementsByName("subtotal")[i].value;

	}

	$("#total").html("$ " + total);

    $("#total_evento").val(total);

    evaluar();

  }



  function evaluar(){

  	if (detalles>0)

    {

      $("#btnGuardar").show();

    }

    else

    {

      $("#btnGuardar").show();

      cont=0;

    }

  }



  function eliminarDetalle(indice){

  	$("#fila" + indice).remove();

  	//calcularTotales();

	modificarSubototales();

  	detalles=detalles-1;

  	evaluar();

  }



  $(function () {

    $(document).on('click', '.borrar', function (event) {

        event.preventDefault();

        $(this).closest('tr').remove();

		modificarSubototales();

    });

	});



init();