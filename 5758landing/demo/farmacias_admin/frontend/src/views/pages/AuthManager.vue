<template>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
        <CContainer>
            <CRow class="justify-content-center">
                <CCol :md="8">
                    <CCardGroup>
                        <CCard class="text-white bg-primary py-5" style="width: 100%">
                            <CCardBody class="text-center">
                                <div>
                                    <img src="../../assets/images/logo.png" style="max-width: 200px; margin: auto; margin-top: 16%">
                                </div>
                            </CCardBody>
                        </CCard>
                        <CCard class="p-4">
                            <CCardBody>
                                <h1>Iniciar sesión</h1>
                                <p class="text-medium-emphasis">Ingresa tus datos para iniciar sesión</p>
                                <div class="mb-3">
                                    <div class="text-muted mb-1">
                                        Iniciar sesión a través de:
                                    </div>
                                    <select class="form-control" v-model="iniciarCon">
                                        <option value="usuario">Nombre de usuario</option>
                                        <option value="correo">Correo electrónico</option>
                                        <option value="corporativo">Código de corporativo</option>
                                    </select>
                                </div>
                                <CInputGroup class="mb-3">
                                    <CInputGroupText>
                                        <CIcon icon="cil-user"/>
                                    </CInputGroupText>
                                    <CFormInput
                                        :placeholder="(iniciarCon !== 'corporativo') ? 'Nombre de usuario o correo' : 'Corporativo'"
                                        autocomplete="username"
                                        v-model="username"
                                    />
                                </CInputGroup>
                                <CInputGroup class="mb-4">
                                    <CInputGroupText>
                                        <CIcon icon="cil-lock-locked"/>
                                    </CInputGroupText>
                                    <CFormInput
                                        type="password"
                                        placeholder="Contraseña"
                                        autocomplete="current-password"
                                        v-model="password"
                                    />
                                </CInputGroup>
                                <div>
                                    <div class="text-danger mb-4" v-if="msg !== ''">{{msg}}</div>
                                    <div>
                                        <button v-if="!loginProcess" class="btn btn-primary w-100" @click="login">Iniciar sesión</button>
                                        <div v-else class="text-muted">
                                            <img :src="loadingImg" style="max-width: 30px"/> Cargando
                                        </div>
                                    </div>
                                </div>
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
import {mapGetters, mapMutations, useStore} from "vuex";
import loadingImg from '@/assets/images/loading.gif'
import Select from "@/views/forms/Select.vue";

export default {
    name: 'Login',
    components: {Select},
    data() {
        return {
            sessionStarted: false,
            username: '',
            password: '',
            msg: '',
            loginProcess: false,
            domain: false,
            iniciarCon: 'usuario',
        };
    },
    setup() {
        return {
            loadingImg,
        }
    },
    computed: {
        ...mapGetters({
            loading: 'loading',
            authLogged: 'authLogged',
            authInfo: 'authInfo',
        })
    },
    watch: {
        authInfo (value) {
            this.sessionStartValidation(value);
        }
    },
    mounted() {
        this.sessionStartValidation();
    },
    methods: {
        ...mapMutations(["authSetInfo"]),
        login() {
            const self = this;

            if (!this.domain) {
                this.domain = window.location.hostname;
            }

            this.loginProcess = true;

            toolbox.doAjax('POST', 'auth/login', {
                nombreUsuario: self.username,
                password: self.password,
                iniciarCon: self.iniciarCon,
                app: self.domain,
            }, function (response) {
                self.authSetInfo(response.data);
                self.$router.push('/apps/directorio');
                self.loginProcess = false;
            }, function (response) {
                self.msg = response.msg;
                self.loginProcess = false;
            })
        },
        sessionStartValidation() {
            if (this.authLogged) {
                if (window.opener) {
                    window.opener.parent.postMessage({
                        k: 'ERSSO_POST_MSG',
                        token: this.authInfo.token,
                        name: this.authInfo.name,
                        email: this.authInfo.email,
                        username: this.authInfo.username,
                        domain: this.domain,
                    }, '*');
                }
                else {
                    console.log('Window Parent Disconected');
                }
            }
            else {
                console.log('User not logged');
            }
        }
    }
}
</script>
