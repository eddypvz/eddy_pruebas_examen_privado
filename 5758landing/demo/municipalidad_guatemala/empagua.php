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
                <form method="post" action="assets/php/contact.php">
                    <div class="row">
                        <div class="col-12">
                            <label class="mb-2">Número de contador</label>
                            <input class="form-control" type="text" id="#contact-name" name="contact-name" placeholder="Escribe aquí" required=""/>
                        </div>
                        <div class="col-12">
                            <button class="btn btn--secondary">Ver saldo a pagar</button>
                        </div>
                    </div>
                </form>
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
