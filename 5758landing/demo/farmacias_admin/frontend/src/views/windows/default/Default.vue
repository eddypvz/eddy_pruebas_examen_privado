<template>
    <CRow>
        <CCol :xs="12">
            <CCard class="mb-4">
                <CCardBody>
                    <div class="text-primary text-center">
                        <div class="my-4">
                            <i class="fas fa-hourglass-half" style="font-size: 4em"></i>
                        </div>
                        <h5>Esta ventana o módulo aún se encuentra en desarrollo</h5>
                        <div>
                            Gracias por tu paciencia
                        </div>
                        <div class="mt-4">
                            <a class="btn btn-primary" href="/#/dashboard">Ir a panel de control</a>
                        </div>
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
