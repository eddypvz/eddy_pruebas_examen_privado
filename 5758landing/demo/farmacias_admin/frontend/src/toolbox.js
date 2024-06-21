import store from "@/store";
import {config} from "/src/config";
import Toastify from 'toastify-js'
import Swal from "sweetalert2";

export default {
    doAjax: (method, url, data, onSuccess, onError, options) => {

        if (!method) method = 'GET';
        method = method.toUpperCase();

        if (!options) options = {};

        let isFile = false;

        if (method === 'FILE') {
            method = 'POST';
            isFile = true;
        }

        /*if (!options.disableLoading) {
            // voy a validar si el token esta ok
            store.dispatch('coreShowLoading');
        }*/
        store.dispatch('showLoading');

        const token = (typeof store.getters.authInfo !== 'undefined' && typeof store.getters.authInfo.token !== 'undefined') ? store.getters.authInfo.token : '';

        let contentType = 'application/json';

        const headers = {
            'Authorization': 'Bearer ' + token,
        };

        if (!isFile) {
            headers["Content-Type"] = contentType;
        }

        const configAjax = {
            method: method,
            headers: new Headers(headers),
        };

        if (!isFile) {
            if (method !== 'GET') {
                configAjax['body'] = JSON.stringify(data);
            }
        }
        else {
            configAjax['body'] = data;
        }

        try {
            fetch(config.backendUrl + '/' + url, configAjax)
                .then(response => response.json())
                .then(data => {
                    if (typeof data.status !== 'undefined') {
                        if (data.status) {
                            if (typeof onSuccess === 'function') {
                                onSuccess(data);
                            }
                            store.dispatch('hideLoading');
                        }
                        else {
                            if (typeof onError === 'function') {
                                onError(data);
                            }
                            if (typeof data['error-code'] !== 'undefined' && data['error-code'] === 'AUTH-001') {
                                window.location.href = '/';
                            }
                            store.dispatch('hideLoading');
                        }
                    }
                })
                .catch((e) => {
                    console.log('API-RequestError:'+ e);
                    store.dispatch('hideLoading');
                });
        }
        catch (e) {
            if (typeof onError === 'function') {
                onError(data);
            }
            store.dispatch('hideLoading');
        }
    },
    alert(text, type) {

        if (!type) type = 'info';

        if (type === 'success') {
            type = "#03bb03";
        }
        else if (type === 'danger') {
            type = "#e00e35";
        }
        else {
            type = "#50bbda";
        }

        Toastify({
            text: text,
            duration: 3000,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
            style: {
                background: type,
            }
        }).showToast();
    },
    confirm(title, onSuccess) {
        Swal.fire({
            title: title,
            showCancelButton: true,
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof onSuccess === 'function') {
                    onSuccess();
                }
            }
        })
    },
    prepareForTable(data) {
        const dataArray = [];
        Object.keys(data).map(function (a){
            dataArray[a] = data[a];
        });
        return dataArray;
    },
    isEmpty(value) {
        if (value === '') {
            return true;
        }
        else {
            return false;
        }
    }
}
