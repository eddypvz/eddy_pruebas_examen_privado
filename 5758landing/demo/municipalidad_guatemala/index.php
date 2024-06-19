<?php
include_once('header.php');
?>
<section class="hero hero-lead hero-lead-1 bg-gray" id="hero">
    <div class="hero-cotainer">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center">
                    <div class="hero-content">
                        <h1 class="hero-title">¡Nuevo! Portal de atención al vecino</h1>
                        <p class="hero-desc">Presentamos el nuevo portal de atención al vecino (PAV), un portal en el cual se encuentran centralizadas las atenciones que necesitas relacionadas con la Municipalidad de Guatemala. <br> Este no es un sitio real, únicamente es un demo</p>
                        <div class="hero-action">
                            <a class="btn btn--primary scroll-to px-3" href="#services">Ver servicios disponibles</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="hero-img"><img src="assets/images/hero/heromuni.png" alt="image"/></div>
                </div>
            </div>
            <!-- End .row-->
        </div>
        <!-- End .container-->
        <div class="shape-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
            </svg>
        </div>
    </div>
    <!-- End .hero-container-->
</section>
<section class="features text-center pt-50" id="services">
    <div class="container">
        <div class="row clearfix">
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="heading heading-2 text-center">
                    <p class="heading-subtitle">Bienvenido al Portal de Atención al Vecino - PAV</p>
                    <h2 class="heading-title">Servicios disponibles</h2>
                </div>
            </div>
            <!-- End .col-lg-6 -->
        </div>
        <!-- End .row  -->
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="feature-card">
                    <div class="iconoServicio">
                        <i class="fas fa-faucet-drip"></i>
                    </div>
                    <div class="feature-content">
                        <h3>EMPAGUA</h3>
                        <p>Consulta tus recibos, paga en línea, reporta averías y mucho más</p>
                        <div class="text-center">
                            <a class="btn btn--primary p-2 mt-4" href="empagua.php">Entrar</a>
                        </div>
                    </div>
                </div>
                <!-- End .feature-card -->
            </div>
            <div class="col-12 col-lg-4">
                <div class="feature-card">
                    <div class="iconoServicio">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Pago de IUSI</h3>
                        <p>Consulta tus impuestos, paga en línea, consulta tu historial y mucho más</p>
                        <div class="text-center">
                            <a class="btn btn--primary p-2 mt-4" href="#">Entrar</a>
                        </div>
                    </div>
                </div>
                <!-- End .feature-card -->
            </div>
            <div class="col-12 col-lg-4">
                <div class="feature-card">
                    <div class="iconoServicio">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="feature-content">
                        <h3>EMETRA</h3>
                        <p>Verifica tus multas, paga en línea, solicita exoneraciones, etc.</p>
                        <div class="text-center">
                            <a class="btn btn--primary p-2 mt-4" href="#">Entrar</a>
                        </div>
                    </div>
                </div>
                <!-- End .feature-card -->
            </div>
            <div class="col-12 col-lg-4">
                <div class="feature-card">
                    <div class="iconoServicio">
                        <i class="fas fa-fire-extinguisher"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Bomberos Municipales</h3>
                        <p>Reporta incidentes, observa alertas y recomendaciones</p>
                        <div class="text-center">
                            <a class="btn btn--primary p-2 mt-4" href="#">Entrar</a>
                        </div>
                    </div>
                </div>
                <!-- End .feature-card -->
            </div>
            <div class="col-12 col-lg-4">
                <div class="feature-card">
                    <div class="iconoServicio">
                        <i class="fas fa-faucet-drip"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Policía Municipal</h3>
                        <p>Reporta incidentes a la policía, observa recomendaciones y noticias</p>
                        <div class="text-center">
                            <a class="btn btn--primary p-2 mt-4" href="#">Entrar</a>
                        </div>
                    </div>
                </div>
                <!-- End .feature-card -->
            </div>
        </div>
        <!-- End .row-->
    </div>
    <!-- End .container-->
</section>
<section class="cta" id="action">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8 offset-lg-2">
                <div class="heading text-center">
                    <p class="heading-subtitle">¿Comentarios o sugerencias?</p>
                    <h2 class="heading-title">Contáctanos</h2>
                </div>
                <form class="contactForm" method="post" action="assets/php/contact.php">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <input class="form-control" type="text" id="#contact-name" name="contact-name" placeholder="Nombre" required=""/>
                        </div>
                        <div class="col-12 col-md-6">
                            <input class="form-control" type="email" id="#contact-email" name="contact-email" placeholder="Correo electrónico" required=""/>
                        </div>
                        <div class="col-12 col-md-6">
                            <input class="form-control" type="text" id="#contact-phone" name="contact-phone" placeholder="Teléfono" required=""/>
                        </div>
                        <div class="col-12 col-md-6">
                            <input class="form-control" type="text" id="#contact-company" name="contact-company" placeholder="Zona en la que vives" required=""/>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" id="contact-infos" placeholder="Comentarios" name="contact-infos" cols="30" rows="10"></textarea>
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn btn--secondary">Enviar</button>
                        </div>
                        <div class="col-12">
                            <div class="contact-result"></div>
                        </div>
                    </div>
                </form>
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
