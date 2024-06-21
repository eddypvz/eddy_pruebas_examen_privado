<template>
    <CRow>
        <CCol :xs="12">
            <CCard class="mb-4">
                <CCardBody>
                    <div class="text-primary">
                        <h5>Bienvenido, al Sistema de Administración de Farmacias</h5>
                    </div>
                    <div>
                        Este proyecto ha sido desarrollado por Eddy Pérez, como prototipo para el examen general privado del área de Desarrollo de Sistemas.
                    </div>
                    <div class="my-4 text-center">
                        <a class="btn btn-primary" href="https://farmaciasdemo.software5758.com/ER_html/" target="_blank">Ver diagrama ER</a>
                    </div>
                    <div class="mt-5">
                        <h4>Avance para desarrollo de este proyecto</h4>
                        <ul class="list list-group">
                            <li class="list-group-item">
                                <b>Configuración de estructura y core: 100% desarrollado</b>
                                <div class="ps-3">
                                    <div>Configuración de framework</div>
                                    <div>Desarrollo de adaptaciones para plantilla visual en frontend (Vue JS)</div>
                                    <div>Configuración de node js y scripts para build</div>
                                    <div>Desarrollo de middleware para evitar problemas por CORS (Cross-origin resource sharing)</div>
                                    <div>Desarrollo de módulo de acceso, configuración y carga de permisos</div>
                                    <div>Desarrollo de controlador para manejo de sesiones y tokens</div>
                                    <div>Desarrollo de sistema de menús dinámico según accesos</div>
                                    <div>Desarrollo de toolbox para frontend (peticiones xhr, alertas, etc)</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <b>Configuración de estructura de frontend: 100% desarrollado</b>
                                <div class="ps-3">
                                    <div>Creación de marca, logotipos, colores</div>
                                    <div>Conexión estándar entre VueJs (frontend) hacia backend</div>
                                    <div>Desarrollo de inicio de sesión, ruteo y estructura de rutas</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <b>Módulo de usuarios: 100% desarrollado</b>
                                <div class="ps-3">
                                    <div>Inicio de sesión</div>
                                    <div>Cierre de sesión</div>
                                    <div>Configuración de roles según accesos de sistema</div>
                                    <div>Creación, edición y eliminación de usuarios</div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <b>Módulo de configuración: 100% desarrollado</b>
                                <div class="ps-3">
                                    <div>Desarrollo de ventana para configuración</div>
                                    <div>Desarrollo de funciones para obtención de configuración</div>
                                    <div>Desarrollo de configuración para reglas de passwords</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </CCardBody>
            </CCard>
        </CCol>
    </CRow>
</template>

<script>
import toolbox from "@/toolbox";
import {mapMutations} from "vuex";
import {config} from "@/config";
import Toolbox from "@/toolbox";

export default {
    name: 'MisApps',
    computed: {
        Toolbox() {
            return Toolbox
        }
    },
    data() {
        return {

        };
    },
    mounted() {

    },
    methods: {
    }
}
</script>
