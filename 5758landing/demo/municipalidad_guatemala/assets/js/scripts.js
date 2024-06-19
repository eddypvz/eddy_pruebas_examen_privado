let montoDePago = 0; // acá voy a mandar el monto, es inseguro ya que se puede cambiar el monto acá y eso cobraría. Se soluciona consultando el recibo nuevamente del lado del server. Como me invento el monto, no puedo hacerlo ahora.
let montoDePagoRaw = 0; // acá voy a mandar el monto, es inseguro ya que se puede cambiar el monto acá y eso cobraría. Se soluciona consultando el recibo nuevamente del lado del server. Como me invento el monto, no puedo hacerlo ahora.

function validarRecibo() {

    $('.cargando').show();
    const noRecibo = $('#contador').val();
    const tipoPago = $('#tipoPago').val();

    // Pago del recibo
    $.ajax({
        type: "GET",
        url: "ws.php?m=empagua&opt=monto_recibo&contador=" + noRecibo + '&tipo=' + tipoPago,
        data: {
            recibo: noRecibo,
        },
        success: function(response) {
            $('.cargando').hide();
            if (response.status) {
                montoDePago = response.data.monto;
                montoDePagoRaw = response.data.montoRaw;
                $('#montoPago').html(response.data.monto);
                $('#noContador').html(response.data.contador);
                $('#noRecibo').html(response.data.recibo);
                $('#fechaVencimiento').html(response.data.fechaVencimiento);

                // divs para pago
                $('#consultaRecibo').hide();
                $('#descripcionRecibo').show();
            }
            else {
                Swal.fire({
                    title: 'Error',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'Continuar'
                })
            }

            /*Swal.fire({
                title: 'Operación exitosa',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'Continuar'
            })*/
        },
        error: function (response) {
            Swal.fire({
                title: 'Error',
                text: response.message,
                icon: 'error',
                confirmButtonText: 'Continuar'
            })
            $('.cargando').hide();
        }
    });
}

function pagoEmpagua() {

    $('.cargando').show();
    const correoElectronico = $('#correoElectronico').val();
    const noTarjeta = $('#noTarjeta').val();
    const nombreTarjeta = $('#nombreTarjeta').val();
    const mesTarjeta = $('#mesTarjeta').val();
    const anioTarjeta = $('#anioTarjeta').val();
    const cvvTarjeta = $('#cvvTarjeta').val();
    const noContador = $('#contador').val();


    // Pago del recibo
    $.ajax({
        type: "POST",
        url: "ws.php?m=empagua&opt=pagar_recibo",
        data: {
            correoElectronico: correoElectronico,
            contador: noContador,
            monto: noContador,
            noTarjeta: noTarjeta,
            nombreTarjeta: nombreTarjeta,
            mesTarjeta: mesTarjeta,
            anioTarjeta: anioTarjeta,
            cvvTarjeta: cvvTarjeta,
            montoDePago: montoDePago,
            montoDePagoRaw: montoDePagoRaw,
        },
        success: function(response) {

            /*$('#montoPago').html(response.data.monto);
            $('#noContador').html(response.data.contador);
            $('#noRecibo').html(response.data.recibo);
            $('#fechaVencimiento').html(response.data.fechaVencimiento);
            $('.cargando').hide();*/

            $('.cargando').hide();
            if (response.status === 1) {
                // divs para pago
                $('#pagoRealizado').show();
                $('#descripcionRecibo').hide();

                Swal.fire({
                    title: 'Operación exitosa',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'Continuar'
                })
            }
            else {
                Swal.fire({
                    title: 'Error',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'Continuar'
                })
            }
        },
        error: function (response) {
            Swal.fire({
                title: 'Error',
                text: response.msg,
                icon: 'error',
                confirmButtonText: 'Continuar'
            })
            $('.cargando').hide();
        }
    });
}

function reiniciar() {
    $('#noTarjeta').val('');
    $('#nombreTarjeta').val('');
    $('#mesTarjeta').val('');
    $('#anioTarjeta').val('');
    $('#cvvTarjeta').val('');
    $('#contador').val('');
    location.reload();
}
