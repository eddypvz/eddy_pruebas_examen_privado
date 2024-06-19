<?php
function responseJson($status, $msg, $data = []) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status' => ($status) ? 1 : 0,
        'message' => $msg,
        'data' => $data,
    ]);
    die();
}

include_once('inc/empagua_calculo_recibo.php');

$module = $_GET['m'] ?? false;
$opt = $_GET['opt'] ?? false;

if (empty($module)) {
    responseJson(false, 'Módulo inválido');
}

if (!empty($opt)) {

    // EMPAGUA
    if ($module === 'empagua') {

        $empagua = new EMPAGUA();

        if ($opt === 'monto_recibo') {
            $contador = $_GET['contador'] ?? false;
            $tipo = $_GET['tipo'] ?? false;
            if (empty($contador)) {
                responseJson(false, 'Número de contador inválido');
            }
            $empagua->calcularRecibo($contador, $tipo);
        }
        else if ($opt === 'pagar_recibo') {
            $data = $_POST;
            $empagua->realizarPagoRecibo($data);
        }

    }

    // BOMBEROS
    if ($module === 'bomberos') {
        // este demo del servicio no estará activo ni los otros
    }

}
else {
    responseJson(false, 'Operación inválida');
}
