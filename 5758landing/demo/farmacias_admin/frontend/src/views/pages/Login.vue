<template>
    <div class="min-vh-100 d-flex flex-row align-items-center bodyBackground">
        <CContainer>
            <CRow class="justify-content-center">
                <CCol :md="8">
                    <CCardGroup>
                        <CCard class="text-white bg-light py-5" style="width: 100%">
                            <CCardBody class="text-center">
                                <div>
                                    <img src="../../assets/images/logo-dark.png" style="max-width: 200px; margin: auto; margin-top: 5%">
                                </div>
                            </CCardBody>
                        </CCard>
                        <CCard class="p-4">
                            <CCardBody>
                                <h1>Iniciar sesión</h1>
                                <p class="text-medium-emphasis">Nombre de usuario</p>
                                <CInputGroup class="mb-3">
                                    <CInputGroupText>
                                        <CIcon icon="cil-user" class="text-dark"/>
                                    </CInputGroupText>
                                    <CFormInput
                                        placeholder="Escribe aquí"
                                        autocomplete="username"
                                        v-model="username"
                                    />
                                </CInputGroup>
                                <p class="text-medium-emphasis">Contraseña</p>
                                <CInputGroup class="mb-4">
                                    <CInputGroupText>
                                        <CIcon icon="cil-lock-locked" class="text-dark"/>
                                    </CInputGroupText>
                                    <CFormInput
                                        type="password"
                                        placeholder="Escribe aquí"
                                        autocomplete="current-password"
                                        v-model="password"
                                    />
                                </CInputGroup>
                                <CRow>
                                    <div class="text-danger mb-4" v-if="msg !== ''">{{msg}}</div>
                                    <CCol :xs="12" class="text-center">
                                        <CButton v-if="!loginProcess" color="primary" class="px-4 w-100" @click="login">Iniciar sesión</CButton>
                                        <div v-else class="text-muted">
                                            <img :src="loadingImg" style="max-width: 30px"> Cargando
                                        </div>
                                        <!--<div>
                                            <CButton color="link" class="px-0 mt-2 text-dark small" @click="$router.push('/reset-password')">
                                                Olvidé mi contraseña
                                            </CButton>
                                        </div>-->
                                    </CCol>
                                </CRow>
                            </CCardBody>
                        </CCard>
                    </CCardGroup>
                </CCol>
            </CRow>
        </CContainer>
    </div>
</template>

<script>
import toolbox from "@/toolbox";
import {mapMutations, useStore} from "vuex";
import loadingImg from '@/assets/images/loading.gif'
import Select from "@/views/forms/Select.vue";

export default {
    name: 'Login',
    components: {Select},
    data() {
        return {
            username: '',
            password: '',
            msg: '',
            loginProcess: false,
            iniciarCon: 'usuario',
        };
    },
    setup() {
        return {
            loadingImg,
        }
    },
    methods: {
        ...mapMutations(["authSetInfo"]),
        login() {
            const self = this;
            this.loginProcess = true;
            const domain = window.location.hostname;
            toolbox.doAjax('POST', 'auth/login', {
                nombreUsuario: self.username,
                password: self.password,
                iniciarCon: self.iniciarCon,
            }, function (response) {
                self.authSetInfo(response.data);

                self.$router.push('/dashboard');

                self.loginProcess = false;
            }, function (response) {
                self.msg = response.msg;
                self.loginProcess = false;
            })
        }
    }
}
</script>
