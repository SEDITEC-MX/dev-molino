$( document ).ready(function() { 
    let nombreEvento;
    let fechaEvento;
    let tipoEvento;

    //Tablas
    let tablaCobros = $("#detallesCobros").DataTable();
    let tablaPagos = $("#detallesPagos").DataTable();

    $("#imprimirPDF").on("click",function(e) {
        printPDF();
    });

    function generateTableData() {
        var data = [];
    
        // Fila de encabezado
        var headerRow = [
            { text: 'Servicio', style: {color: '#F5873E', bold: true} },
            { text: 'Categoría Servicio', style: {color: '#F5873E', bold: true} },
            { text: 'Cantidad', style: {color: '#F5873E', bold: true} },
            { text: 'Precio', style: {color: '#F5873E', bold: true} },
            { text: 'Subtotal', style: {color: '#F5873E', bold: true} }
        ];
        data.push(headerRow);
    
        // Generación del cuerpo de la tabla con un bucle for
        for (var i = 1; i <= 90; i++) {
            var row = [
                { text: 'Celda ' + i },
                { text: 'Celda ' + i },
                { text: 'Celda ' + i },
                { text: 'Celda ' + i },
                { text: 'Celda ' + i }
            ];
            data.push(row);
        }
    
        return data;
    }

    function generarTablaServicios() {
        let data = [];
        let headerRow = [
            { text: 'Servicio', style: 'tableHeader' },
            { text: 'Categoría Servicio', style: 'tableHeader' },
            { text: 'Cantidad', style: 'tableHeader' },
            { text: 'Precio', style: 'tableHeader' },
            { text: 'Subtotal', style: 'tableHeader' }
        ];
        data.push(headerRow);
        
        $("#detalles .filas").each(function () {
            let servicio = $(this).find("td:eq(1)").text();
            let categoriaServicio = $(this).find("td:eq(2)").text();
            let cantidad = $(this).find("td:eq(3) input").val();
            let precio = $(this).find("td:eq(4) input").val();
            precio = new Intl.NumberFormat('es-MX', 
            {style: "currency", currency: "MXN"}).format(precio);
            let subtotal = $(this).find("td:eq(5)").text();
            let row = [
                { text: servicio, style: 'tableBody' },
                { text: categoriaServicio, style: 'tableBody' },
                { text: cantidad, style: 'tableBody' },
                { text: precio, style: 'tableBody' },
                { text: subtotal, style: 'tableBody' },
            ]
            data.push(row)
        });
    
        return data;
    }

    function generarTablaCobros() {
        let data = [];
        let headerRow = [
            { text: 'Fecha', style: 'tableHeader' },
            { text: 'Monto', style: 'tableHeader' },
            { text: 'Método', style: 'tableHeader' },
        ];
        data.push(headerRow);
        tablaCobros.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            let fila = this.data();
            let fecha = fila[1];
            fecha = fecha.slice(0, 10);
            let monto = fila[2];
            let metodo = fila[3];
            let row = [
                {text: fecha, style: 'tableBody'},
                {text: monto, style: 'tableBody'},
                {text: metodo, style: 'tableBody'}
            ]
            data.push(row);
        });
        return data;
    }

    function generarTablaPagos() {
        let data = [];
        let headerRow = [
            { text: 'Fecha del pago', style: 'tableHeader' },
            { text: 'Proveedor', style: 'tableHeader' },
            { text: 'Monto del pago', style: 'tableHeader' },
            { text: 'Método de pago', style: 'tableHeader' },
        ];
        data.push(headerRow);
        tablaPagos.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            let fila = this.data();
            let fecha = fila[1];
            let proveedor = fila[2];
            let monto = fila[3];
            let metodo = fila[4];
            let row = [
                {text: fecha, style: 'tableBody'},
                {text: proveedor, style: 'tableBody'},
                {text: monto, style: 'tableBody'},
                {text: metodo, style: 'tableBody'}
            ]
            data.push(row);
        });
        return data;
    }

    function getReportHeader(){
        const headerContent = [
            {
                    layout: 'noBorders',

                table:{
                    widths: ['50%', '50%'],
                    body: [
                        [
                            {
                                image: img,
                                width: 100,
                                alignment: 'left'

                            },
                            {
                                stack: [
                                    'Rancho el Molino s/n',
                                    'Chichimequillas, El Marqués',
                                    'Querétaro',
                                    '442 312 01 11',
                                    {
                                        text: 'informacion@casaelmolino.com.mx',
                                        color: '#F5873E' 
                                    }
                                    
                                ],
                                alignment: 'right'
                            },
                        ]
                    ]
                },
                margin: [50,40]
            }
        ]
        return headerContent
    }

    let footerContent = function(currentPage, pageCount) {
        return {
            text: currentPage.toString() + ' de ' + pageCount,
            alignment: 'right',
            color: 'gray',
            margin: [50, 10, 50, 2],
            italics: true
        };
    };

    function printPDF(){

        nombreEvento = $("#nombre_evento").val();
        fechaEvento = $("#fecha_evento").val();
        tipoEvento = $(".selectpicker").val();
        
        let saldoPendiente = $("#saldoPendiente").text();
        let saldoPendienteLetra = numeroALetras(Math.abs(calcularSaldoPendiente()), { //Esta función está en detalle-evento
            plural: "PESOS",
            singular: "PESO",
            centPlural: "CENTAVOS",
            centSingular: "CENTAVO"
          });
        if (calcularSaldoPendiente() < 0){
            saldoPendienteLetra = "Menos " + saldoPendienteLetra;
        }
        let totalServicios = $("#total").text();
        let totalCobros = $("#totalCobros").text();
          
        const documentDefinition = {
            pageMargins: [50, 130, 50, 40],
            header: function () {
                return getReportHeader();
            } ,
            footer: footerContent,
            content: [
                {
                    layout: 'noBorders',
                    table: {   
                        widths: ["20%", "40%"],
                        body: [
                            [ {text: 'Evento: ', color: '#F5873E', bold: true}, {text: nombreEvento, bold: true} ],
                            [ {text: 'Fecha: ', color: '#F5873E', bold: true}, {text: fechaEvento } ],
                            [ {text: 'Tipo de evento: ', color: '#F5873E', bold: true}, {text: tipoEvento} ],
                        ],
                    },
                },
                {
                    layout: 'noBorders',
                    table: {
                        widths: ["100%"],
                        body:[
                            [{text: 'SERVICIOS CONTRATADOS', style: "separador"}]
                        ]
                    },
                    margin: [0,15,0,5]
                },
                {
                    table: {
                        headerRows: 1, // Número de filas en el encabezado
                        widths: ['*', '25%', '10%', '10%', '12%'], // Ancho de las columnas
                        body: generarTablaServicios()
                    },
                    layout: {
                        hLineWidth: function(i, node) {
                            if (i === 0 || i === 1) {
                                // Borde negro arriba y abajo para las filas 0 y 1 (encabezado)
                                return 1;
                            }
                            return 0.1; 

                        },
                        vLineWidth: function(i) {
                            return 0; // Sin bordes verticales
                        },
                        hLineColor: function(i) {
                            if (i === 0 || i === 1) {
                                return "black";
                            }
                            return "gray";
                        },
                        paddingLeft: function(i) {
                            return 8; // Espaciado izquierdo para el contenido de la celda
                        },
                        paddingRight: function(i) {
                            return 8; // Espaciado derecho para el contenido de la celda
                        }
                    }
                },
                {
                    table: {
                        
                        widths: ['50%', '50%'],
                        body:[
                            [
                                {text: 'TOTAL', style: "totalIzq" },
                                {text: totalServicios, style: "totalDer" },
                            ],
                        ]
                    },
                    margin: [0,15,0,5],
                    layout: {
                        hLineWidth: function(i, node) {
                            return 1;

                        },
                        vLineWidth: function(i) {
                            return 0; // Sin bordes verticales
                        },
                        hLineColor: function(i) {
                            return "black";
                        },
                        paddingLeft: function(i) {
                            return 10; // Espaciado izquierdo para el contenido de la celda
                        },
                        paddingRight: function(i) {
                            return 10; // Espaciado derecho para el contenido de la celda
                        },
                        paddingTop: function(i) {
                            return 2; // Espaciado derecho para el contenido de la celda
                        },
                        paddingBottom: function(i) {
                            return 2; // Espaciado derecho para el contenido de la celda
                        }
                    }
                },
                {
                    layout: 'noBorders',
                    table: {
                        widths: ["100%"],
                        body:[
                            [{text: 'COBROS REALIZADOS', style: "separador"}]
                        ]
                    },
                    margin: [0,30,0,5]
                },
                {
                    table: {
                        headerRows: 1, 
                        widths: ['17%', '20%', '23%'],
                        body: generarTablaCobros()
                    },
                    layout: {
                        hLineWidth: function(i, node) {
                            if (i === 0 || i === 1) {
                                return 1;
                            }
                            return 0.1; 
                        },
                        vLineWidth: function(i) {
                            return 0;
                        },
                        hLineColor: function(i) {
                            if (i === 0 || i === 1) {
                                return "black";
                            }
                            return "gray";; 
                        },
                        paddingLeft: function(i) {
                            return 10; 
                        },
                        paddingRight: function(i) {
                            return 10; 
                        }
                    },
                    margin: [120,0,0,0]
                },
                {
                    table: {
                        
                        widths: ['40%', '40%'],
                        body:[
                            [
                                {text: 'TOTAL', style: "totalIzq" },
                                {text: totalCobros, style: "totalDer" },
                            ],
                        ]
                    },
                    margin: [0,15,0,5],
                    layout: {
                        hLineWidth: function(i, node) {
                            return 1;

                        },
                        vLineWidth: function(i) {
                            return 0; // Sin bordes verticales
                        },
                        hLineColor: function(i) {
                            return "black";
                        },
                        paddingLeft: function(i) {
                            return 10; // Espaciado izquierdo para el contenido de la celda
                        },
                        paddingRight: function(i) {
                            return 10; // Espaciado derecho para el contenido de la celda
                        },
                        paddingTop: function(i) {
                            return 2; // Espaciado derecho para el contenido de la celda
                        },
                        paddingBottom: function(i) {
                            return 2; // Espaciado derecho para el contenido de la celda
                        }
                    }
                },
                {
                    layout: 'noBorders',
                    table: {
                        widths: ["*"],
                        body:[
                            [{text: 'SALDO PENDIENTE', style: "final"}],
                            [{text: saldoPendiente, style: "final"}],
                            [{text: saldoPendienteLetra, style: "final"}]
                        ]
                    },
                    margin: [0,30,0,5]
                },
            ],
            styles:{
                tableHeader:{
                    color: '#F5873E',
                    bold: true,
                },
                tableBody:{
                    fontSize: 9,
                    border: [0, 0, 0, 1], // Borde inferior
                },
                separador: {
                    color: 'white',
                    alignment: 'center',
                    bold: true,
                    fontSize: 12,
                    fillColor: '#F5873E'
                },
                final: {
                    color: 'white',
                    alignment: 'center',
                    bold: true,
                    fillColor: '#F5873E'
                },
                totalIzq:{
                    color: "#F5873E",
                    bold: true,
                    fontSize: 14
                },
                totalDer:{
                    color: "#F5873E",
                    alignment: 'right',
                    bold: true,
                    fontSize: 14
                }
            }
        };
    
            // Crear el documento PDF
            const pdfDoc = pdfMake.createPdf(documentDefinition);
    
            // Descargar el PDF
        //   pdfDoc.download('ejemplo.pdf');
        
        
        //Este código es para abrirlo en lugar de descargarlo
        var createPdf = pdfMake.createPdf(documentDefinition);
        var base64data = null;
    
        createPdf.getBase64(function(encodedString) {
            base64data = encodedString;
            console.log(base64data );
    
    
            var byteCharacters = atob(base64data);
            var byteNumbers = new Array(byteCharacters.length);
            for (var i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            var byteArray = new Uint8Array(byteNumbers);
            var file = new Blob([byteArray], { type: 'application/pdf;base64' });
            var fileURL = URL.createObjectURL(file);
            window.open(fileURL);
        })
    }

    let img = "data:image/png;base64,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"
});




