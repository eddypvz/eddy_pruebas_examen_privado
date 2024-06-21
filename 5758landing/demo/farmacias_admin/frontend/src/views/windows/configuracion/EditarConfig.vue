<template>
    <CRow>
        <CCol :xs="12" class="p-3">

            <CCard class="mb-4">
                <CCardHeader>
                    <strong>Configuración de sistema</strong>
                </CCardHeader>
                <CCardBody>

                    <AccordionList v-model:state="state" :open-multiple-items="false">
                        <AccordionItem id="configSecurity">
                            <template #summary>Configuración de seguridad</template>
                            <div>
                                <div class="mb-5">
                                    <div>
                                        <div class="row">
                                            <div class="col-12 col-sm-6">
                                                <h6 class="text-primary">Fortaleza de contraseña</h6>
                                                <div class="mb-3">
                                                    <div class="mb-2">
                                                        <label class="form-label">Longitud mínima</label>
                                                        <input type="number" class="form-control" placeholder="Escribe aquí" v-model="passwordSecurity.longitudPass">
                                                    </div>
                                                    <div>
                                                        <input type="checkbox" placeholder="Escribe aquí" v-model="passwordSecurity.letrasPass" class="me-2" value="1">
                                                        <label class="form-label">Exigir mayusculas</label>
                                                    </div>
                                                    <div>
                                                        <input type="checkbox" placeholder="Escribe aquí" v-model="passwordSecurity.numerosPass" class="me-2" value="1">
                                                        <label class="form-label">Exigir números</label>
                                                    </div>
                                                    <div>
                                                        <input type="checkbox" placeholder="Escribe aquí" v-model="passwordSecurity.caracteresPass" class="me-2" value="1">
                                                        <label class="form-label">Exigir carácteres especiales</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </AccordionItem>
                    </AccordionList>
                    <div>
                        <div class="col-12 mt-4 text-end">
                            <button @click="save" class="btn btn-primary me-4">Guardar</button>
                            <button @click="$router.push('/usuarios/listado')" class="btn btn-danger">Cancelar</button>
                        </div>
                    </div>
                </CCardBody>
            </CCard>
        </CCol>
    </CRow>
</template>

<script>
import toolbox from "@/toolbox";
import Select from "@/views/forms/Select.vue";
import {AccordionList, AccordionItem} from "vue3-rich-accordion";
import "vue3-rich-accordion/accordion-library-styles.css";
import VueJsoneditor from 'vue3-ts-jsoneditor';
import {ref} from "vue";

export default {
    name: 'Tables',
    components: {
        Select,
        AccordionList,
        AccordionItem,
        VueJsoneditor,
    },
    data() {
        return {
            state: {},

            id: 0,
            editorForgot: null,
            whatsappPasswordForgotLoad: false,
            whatsappPasswordForgotMode: 'tree',
            whatsappPasswordForgot: "",
            whatsappPasswordForgotToken: "",
            whatsappPasswordForgotUrl: "",

            editorSend: null,
            whatsappCredentialsSendLoad: false,
            whatsappCredentialsSendMode: 'tree',
            whatsappCredentialsSend: "",
            whatsappCredentialsSendToken: "",
            whatsappCredentialsSendUrl: "",

            editorSendEdit: null,
            whatsappCredentialsSendEditLoad: false,
            whatsappCredentialsSendEditMode: 'tree',
            whatsappCredentialsSendEdit: "",
            whatsappCredentialsSendEditToken: "",
            whatsappCredentialsSendEditUrl: "",

            mailgun: {
                apiKey: '',
                domain: '',
                from: '',
                subject: '',
            },
            templateHtmlAsunto: '',
            templateHtml: '',
            userResetTemplateHtmlAsunto: '',
            userResetTemplateHtml: '',
            passwordSecurity: {
                longitudPass: 6,
                letrasPass: 0,
                numerosPass: 0,
                caracteresPass: 0,
            },

            jsoneditorOptions: {
                "schema": {},
                "startval": {},
                "config": {
                    "options": {
                        "theme": "spectre",
                        "object_layout": "grid"
                    },
                    "callbacks": {
                        "button": {},
                        "ace": {}
                    }
                }
            },
        };
    },
    mounted() {
        //this.id = (typeof this.$route.params.id !== 'undefined') ? parseInt(this.$route.params.id) : 0;
        //console.log(this.id);

        this.getData();
    },
    methods: {
        getData() {
            const self = this;

            toolbox.doAjax('GET', 'configuration/load', {},
                function (response) {

                    if (response.data.whatsappCredentialsSend !== null && response.data.whatsappCredentialsSend) {
                        self.whatsappCredentialsSend = response.data.whatsappCredentialsSend;
                    }
                    self.whatsappCredentialsSendToken = response.data.whatsappCredentialsSendToken;
                    self.whatsappCredentialsSendUrl = response.data.whatsappCredentialsSendUrl;
                    self.whatsappCredentialsSendLoad = true;

                    if (response.data.whatsappPasswordForgot !== null) {
                        self.whatsappPasswordForgot = response.data.whatsappPasswordForgot;
                    }
                    self.whatsappPasswordForgotToken = response.data.whatsappPasswordForgotToken;
                    self.whatsappPasswordForgotUrl = response.data.whatsappPasswordForgotUrl;
                    self.whatsappPasswordForgotLoad = true;


                    if (response.data.whatsappCredentialsSendEdit !== null && response.data.whatsappCredentialsSendEdit) {
                        self.whatsappCredentialsSendEdit = response.data.whatsappCredentialsSendEdit;
                    }
                    self.whatsappCredentialsSendEditToken = response.data.whatsappCredentialsSendEditToken;
                    self.whatsappCredentialsSendEditUrl = response.data.whatsappCredentialsSendEditUrl;
                    self.whatsappCredentialsSendEditLoad = true;


                    self.mailgun = response.data.mailgunNotifyConfig;
                    self.templateHtmlAsunto = response.data.templateHtmlAsunto;
                    self.templateHtml = response.data.userCreateTemplateHtml;
                    self.userResetTemplateHtmlAsunto = response.data.userResetTemplateHtmlAsunto;
                    self.userResetTemplateHtml = response.data.userResetTemplateHtml;

                    self.passwordSecurity.longitudPass = response.data.passwordSecurity.longitudPass || 0;
                    self.passwordSecurity.caracteresPass = response.data.passwordSecurity.caracteresPass || 0;
                    self.passwordSecurity.letrasPass = response.data.passwordSecurity.letrasPass || 0;
                    self.passwordSecurity.numerosPass = response.data.passwordSecurity.numerosPass || 0;
                },
                function (response) {
                    toolbox.alert(response.msg, 'danger');
                });
        },
        save() {
            const self = this;

            toolbox.doAjax('POST', 'configuration/save', {
                    whatsappPasswordForgot: self.whatsappPasswordForgot,
                    whatsappPasswordForgotToken: self.whatsappPasswordForgotToken,
                    whatsappPasswordForgotUrl: self.whatsappPasswordForgotUrl,

                    whatsappCredentialsSend: self.whatsappCredentialsSend,
                    whatsappCredentialsSendToken: self.whatsappCredentialsSendToken,
                    whatsappCredentialsSendUrl: self.whatsappCredentialsSendUrl,

                    whatsappCredentialsSendEdit: self.whatsappCredentialsSendEdit,
                    whatsappCredentialsSendEditToken: self.whatsappCredentialsSendEditToken,
                    whatsappCredentialsSendEditUrl: self.whatsappCredentialsSendEditUrl,

                    mailgun: self.mailgun,
                    templateHtmlAsunto: self.templateHtmlAsunto,
                    templateHtml: self.templateHtml,
                    userResetTemplateHtmlAsunto: self.userResetTemplateHtmlAsunto,
                    userResetTemplateHtml: self.userResetTemplateHtml,
                    passwordSecurity: self.passwordSecurity,
                },
                function (response) {
                    toolbox.alert(response.msg, 'success');
                },
                function (response) {
                    toolbox.alert(response.msg, 'danger');
                });
        },
        focusOutEditor() {
            this.$refs.editorSend.$updateProps({
                mode: 'tree'
            })
            this.$refs.editorForgot.$updateProps({
                mode: 'tree'
            })
            this.$refs.editorSendEdit.$updateProps({
                mode: 'tree'
            })
        },
        onError() {
            //console.log('error')
        }
    }
}
</script>
