<?php
include_once('header.php');
?>
<section class="pagoContainer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-7">
                <div class="heading">
                    <p class="heading-subtitle">EMPAGUA</p>
                    <h2 class="heading-title">Pago recibo de agua</h2>
                </div>
                <div id="consultaRecibo">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label class="mb-2">Número de contador</label>
                            <input class="form-control" type="text" id="contador" placeholder="Escribe aquí" required="" value=""/>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="mb-2">Tipo de pago</label>
                            <select class="form-control" id="tipoPago">
                                <option value="domestico" selected>Doméstico</option>
                                <option value="empresarial">Empresarial</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn--secondary" onclick="validarRecibo()">Ver saldo a pagar</button>
                        </div>
                    </div>
                </div>
                <div id="descripcionRecibo" style="display: none">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-primary">Resumen de pago</h4>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h6>Monto a pagar: <span id="montoPago" class="text-primary">Q.0.00</span></h6>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h6>Número de contador: <span id="noContador" class="text-primary"></span></h6>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h6>Número de recibo: <span id="noRecibo" class="text-primary"></span></h6>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h6>Fecha de vencimiento: <span id="fechaVencimiento" class="text-primary"></span></h6>
                        </div>
                        <div class="col-12 mt-4">
                            <h4 class="text-primary">Datos de pago</h4>
                        </div>
                        <div class="col-6">
                            <label class="mb-2">Correo electrónico</label>
                            <input class="form-control" type="email" id="correoElectronico" placeholder="Escribe aquí" required="" value="" maxlength="16"/>
                        </div>
                        <div class="col-6">
                            <label class="mb-2">Número de tarjeta</label>
                            <input class="form-control" type="text" id="noTarjeta" placeholder="Escribe aquí" required="" value="" maxlength="16"/>
                        </div>
                        <div class="col-6">
                            <label class="mb-2">Nombre en tarjeta</label>
                            <input class="form-control" type="text" id="nombreTarjeta" placeholder="Escribe aquí" required="" value=""/>
                        </div>
                        <div class="col-6">
                            <label class="mb-2">Fecha vencimiento</label>
                            <div class="input-group">
                                <select class="form-control" id="mesTarjeta">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                                <select class="form-control" id="anioTarjeta">
                                    <?php
                                    for($i = date('Y') ; $i < date('Y') + 8; $i++){
                                        print "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="mb-2">Código CVV</label>
                            <input class="form-control" type="password" id="cvvTarjeta" placeholder="Escribe aquí" required="" value=""/>
                        </div>
                        <div class="col-12">
                            <button class="btn btn--secondary" onclick="pagoEmpagua()">Realizar pago</button>
                        </div>
                    </div>
                </div>
                <div id="pagoRealizado" style="display: none">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-primary">Pago realizado con éxito</h4>
                        </div>
                        <div class="col-12 mt-4">
                            <button class="btn btn--secondary" onclick="reiniciar()">Realizar otro pago</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <img src="assets/images/muni/empagua.jpg" style="max-width: 100%">
            </div>
            <!-- End .col-12-->
        </div>
        <!-- End .row-->
    </div>
    <!-- End .container-->
</section>
<?php
include_once('footer.php');
?>
