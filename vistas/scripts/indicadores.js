$(document).ready(function() {
	$("#actualizar").click(function() {
		// Obtener los datos del formulario
		let datosFormulario = $("#formulario").serialize();
		console.log(datosFormulario)


	$.ajax({
		url: "../ajax/indicadores.php?op=actualizar",
	    type: "POST",
		data: datosFormulario,
		
	    success: function(datos)
	    { 
			datos = JSON.parse(datos);
			mostrarTodoIngresos = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[0]);		
			mostrarIngresosHacienda = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[1]);		
			mostrarIngresosBanqueta = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[2]);		
			mostrarIngresosHotel = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[3]);		
			mostrarIngresosMobiliario = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[4]);		
			mostrarIngresosProveedor = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[5]);		
			mostrarIngresosCasa = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[6]);		
			mostrarTodoPagos = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[7]);		
			mostrarPagosHacienda = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[8]);		
			mostrarPagosBanquete = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[9]);		
			mostrarPagosHotel = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[10]);		
			mostrarPagosMobi = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[11]);		
			mostrarPagosProveedor = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[12]);		
			mostrarPagosCasa = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[13]);		
			utilidadTotal = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[14]);
			utilidadHacienda = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[15]);
			utilidadBanquete = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[16]);
			utilidadHotel = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[17]);
			utilidadMobi = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[18]);
			utlidadPro = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[19]);
			utlidadCasa = new Intl.NumberFormat('es-MX', {style: "currency", currency: "MXN"}).format(datos[20]);
			
			
			document.getElementById("ingresos").innerHTML = mostrarTodoIngresos; //mostrarTodoIngresos
			document.getElementById("Hacienda_ingresos").innerHTML = mostrarIngresosHacienda; //mostrarIngresosHacienda
			document.getElementById("Banquete_ingresos").innerHTML = mostrarIngresosBanqueta; //mostrarIngresosBanqueta
			document.getElementById("Hotel_ingresos").innerHTML = mostrarIngresosHotel; //mostrarIngresosHotel
			document.getElementById("Mobi_ingresos").innerHTML = mostrarIngresosMobiliario; //mostrarIngresosMobiliario
			document.getElementById("Proveedor_ingresos").innerHTML = mostrarIngresosProveedor; //mostrarIngresosProveedor
			document.getElementById("Casa_ingresos").innerHTML = mostrarIngresosCasa; //mostrarIngresosCasa
			document.getElementById("egresos").innerHTML = mostrarTodoPagos; //mostrarTodoPagos
			document.getElementById("Hacienda_egresos").innerHTML = mostrarPagosHacienda; //mostrarPagosHacienda
			document.getElementById("Banquete_egresos").innerHTML = mostrarPagosBanquete; //mostrarPagosBanquete
			document.getElementById("Hotel_egresos").innerHTML = mostrarPagosHotel; //mostrarPagosHotel
			document.getElementById("Mobi_egresos").innerHTML = mostrarPagosMobi; //mostrarPagosMobi
			document.getElementById("Proveedor_egresos").innerHTML = mostrarPagosProveedor; //mostrarPagosProveedor
			document.getElementById("Casa_egresos").innerHTML = mostrarPagosCasa; //mostrarPagosCasa
			document.getElementById("utilidad").innerHTML = utilidadTotal; //utilidadTotal
			document.getElementById("Hacienda_utilidad").innerHTML = utilidadHacienda; //utilidadHacienda
			document.getElementById("Banquete_utilidad").innerHTML = utilidadBanquete; //utilidadBanquete
			document.getElementById("Hotel_utilidad").innerHTML = utilidadHotel; //utilidadHotel
			document.getElementById("Mobi_utilidad").innerHTML = utilidadMobi; //utilidadMobi
			document.getElementById("Proveedor_utilidad").innerHTML = utlidadPro; //utlidadPro
			document.getElementById("Casa_utilidad").innerHTML = utlidadCasa; //utlidadCasa

	    },
	    error: function(err){
			bootbox.alert(err);
	    }
	});
});

	$("#graficar").click(function() {
		// let datosFormulario = $("#formulario").serialize();
		let datosFormulario = new FormData();
		let fechaInicio = $("#fecha_inicio").val();
		let fechaFin = $("#fecha_fin").val();
		datosFormulario.append('fecha_inicio', fechaInicio);
		datosFormulario.append('fecha_fin', fechaFin);
		$.ajax({
			url: "../ajax/indicadores.php?op=graficar",
			type: "POST",
			dataType: 'json',
			processData: false,
			contentType: false,
			data: datosFormulario,
			
			success: function(datos)
			{ 
				// Convertir el objeto JSON a una cadena JSON
				let miJson = JSON.stringify(datos);
				// Almacenar la cadena JSON en el almacenamiento local del navegador
				localStorage.setItem('miJson', miJson);
					
				google.charts.load('current', {'packages':['line']});
				google.charts.setOnLoadCallback(function(){
					drawChart(datos)
				});
			},
		
			error: function(err){
				//bootbox.alert(err);
				console.log(err);
			}
		});
	});



	
  	function drawChart(datos) {
		console.log(datos)
		let data = new google.visualization.DataTable();

		let agrupados = Object.keys(datos.egresos[0] || datos.ingresos[0]);
		console.log(agrupados[1]);

		let options = {

		};
		

		//Por meses
		if (agrupados[1] == "mes"){

			// Añadir las columnas
			data.addColumn('date', 'Tiempo')	;
			data.addColumn('number', 'Egresos');
			data.addColumn('number', 'Ingresos');
			data.addColumn('number', 'Utilidad o Pérdida');

			// Añadir periodo de fechas seleccionadas
			fecha_inicio = $("#fecha_inicio").val();
			fecha_fin = $("#fecha_fin").val();
			

			
			let fechas = dateRange(fecha_inicio, fecha_fin);


			for(i=0; i< fechas.length; i++){
				let egreso;
				datos['egresos'].some(function(el){ 
					if (el.tiempo === fechas[i]){
						egreso = parseFloat(el['total'])	
					} 
				});
				
				let ingreso;
				datos['ingresos'].some(function(el){ 
					if (el.tiempo === fechas[i]){
						ingreso = parseFloat(el['total'])	
					} 
				});

				egreso = egreso != undefined ? egreso : 0;
				ingreso = ingreso != undefined ? ingreso : 0;
				let utilidad = ingreso - egreso; 
				let fecha = new Date(Date.parse(fechas[i]));
				data.addRow([fecha, egreso, ingreso, utilidad])
			}

			options = {
				hAxis: {
					viewWindow: {
					// min: 0,
					// max: 56,
					// format: 'yyyy/MM',
				}},
				chart: {
				title: 'Rendimiento',
				subtitle: 'Del: ' + fecha_inicio + ' al ' + fecha_fin
				},
				// width: $(window).width()*0.50,
				height: $(window).height()*0.50,
			};
		
		}

		//Por semanas
		if (agrupados[1] == "semanas"){

			// Añadir las columnas
			data.addColumn('number', 'Semanas')	;
			data.addColumn('number', 'Egresos');
			data.addColumn('number', 'Ingresos');
			data.addColumn('number', 'Utilidad o Pérdida');

			// Añadir periodo de fechas seleccionadas
			fecha_inicio = $("#fecha_inicio").val();
			fecha_fin = $("#fecha_fin").val();
			

			

			for(i=0; i< datos['ingresos'].length; i++){
				let egreso = datos['egresos'][i] != undefined 
				? parseFloat(datos['egresos'][i]['total']) 
				: 0;
				
				let ingreso = datos['ingresos'][i] != undefined 
				? parseFloat(datos['ingresos'][i]['total']) : 
				0;
				
				let utilidad = ingreso - egreso; 
				// let fecha = new Date(Date.parse(fechas[i]));
				let fecha = parseInt(datos['ingresos'][i]['semanas']);
				console.log(fecha)
				console.log(egreso)
				data.addRow([fecha, egreso, ingreso, utilidad])
			}


			options = {
				hAxis: {
					viewWindow: {
					min: datos['ingresos'][0],
					max: datos['ingresos'].slice(-1),
				}},
				chart: {
				title: 'Rendimiento',
				subtitle: 'Del: ' + fecha_inicio + ' al ' + fecha_fin
				},
				// width: $(window).width()*0.50,
				height: $(window).height()*0.50,
			};
			
		}
		

		

		
		let chart = new google.charts.Line(document.getElementById('linechart_material'));
		chart.draw(data, google.charts.Line.convertOptions(options));
	}


	function dateRange(startDate, endDate) {
		let start      = startDate.split('-');
		let end        = endDate.split('-');
		let startYear  = parseInt(start[0]);
		let endYear    = parseInt(end[0]);
		let dates      = [];
	  
		for(let i = startYear; i <= endYear; i++) {
		  let endMonth = i != endYear ? 11 : parseInt(end[1]) - 1;
		  let startMon = i === startYear ? parseInt(start[1])-1 : 0;
		  for(let j = startMon; j <= endMonth; j = j > 12 ? j % 12 || 11 : j+1) {
			let month = j+1;
			let displayMonth = month < 10 ? month : month;
			dates.push([i, displayMonth].join('/'));
		  }
		}
		return dates;
	}

	
});




