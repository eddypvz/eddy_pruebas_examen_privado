<?php
class EMPAGUA {

    public function calcularRecibo($contador, $tipo) {

        sleep(1);

        if ($tipo === 'domestico') {
            $montoRandom = rand(20, 500);
        }
        else {
            $montoRandom = rand(5500, 10000);
        }

        $reciboNo = rand(80000, 90000);

        $date = new DateTime();
        $date->modify('+5 day');
        $fechaVence = $date->format('d/m/Y');
        $moneda = "Q.";

        $data = [
            'recibo' => $reciboNo,
            'contador' => $contador,
            'monto' => $moneda.number_format($montoRandom, 2),
            'montoRaw' => $montoRandom,
            'fechaVencimiento' => $fechaVence,
        ];

        responseJson(true, 'Monto de pago obtenido con éxito', $data);
    }

    public function realizarPagoRecibo($dataPago) {

        if (empty($dataPago['correoElectronico'])) responseJson(false, 'Correo electrónico inválido');
        if (empty($dataPago['contador'])) responseJson(false, 'Contador inválido');
        if (empty($dataPago['monto'])) responseJson(false, 'Monto inválido');
        if (empty($dataPago['noTarjeta'])) responseJson(false, 'Tarjeta inválida');
        if (empty($dataPago['nombreTarjeta'])) responseJson(false, 'Nombre de tarjeta inválido');
        if (empty($dataPago['mesTarjeta'])) responseJson(false, 'Mes inválido');
        if (empty($dataPago['anioTarjeta'])) responseJson(false, 'Año inválido');
        if (empty($dataPago['cvvTarjeta'])) responseJson(false, 'CVV inválido');

        $autorizacion = rand(40000, 90000);

        sleep(2); // para emular que hace algo o que consume un ws

        $data = [
            'autorizacion' => $autorizacion,
            'monto' => $dataPago['montoDePago'] ?? '',
        ];

        // envío correo
        if (!empty($dataPago['montoDePagoRaw']) && $dataPago['montoDePagoRaw'] > 5000) {

            $to      = $dataPago['correoElectronico'];
            $subject = 'Pago de servicio EMPAGUA';
            $message = "Estimado vecino, su recibo ha sido pagado con éxito por un monto de {$data['monto']}, autorización bancaria No.{$autorizacion}";
            $headers = 'From: webmaster@software5758.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

            @mail($to, $subject, $message, $headers);
        }


        $pago = "Su recibo ha sido pagado con éxito por un monto de {$data['monto']}, autorización bancaria No.{$autorizacion}";
        responseJson(true, $pago, $data);
    }
}
