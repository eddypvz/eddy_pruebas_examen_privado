import {createStore} from 'vuex'
import store from "/src/store";
import {config} from "/src/config";

const storagePrefix = 'LC_';

function getStorage(item) {
    let tmp = localStorage.getItem(storagePrefix + item);
    if (tmp) {
        try {
            tmp = JSON.parse(tmp);
            return tmp;
        }
        catch (e) {
            return false;
        }
    }
}
function setStorage(item, data) {
    localStorage.setItem(storagePrefix + item, JSON.stringify(data));
}

export default createStore({
    state: {
        loading: false,
        sidebarVisible: '',
        sidebarUnfoldable: false,
        authInfo: getStorage('authInfo')
    },
    mutations: {
        showLoading(state) {
            state.loading = true;
        },
        hideLoading(state) {
            state.loading = false;
        },
        toggleSidebar(state) {
            state.sidebarVisible = !state.sidebarVisible
        },
        toggleUnfoldable(state) {
            state.sidebarUnfoldable = !state.sidebarUnfoldable
        },
        updateSidebarVisible(state, payload) {
            state.sidebarVisible = payload.value
        },
        authSetInfo(state, payload) {
            setStorage('authInfo', payload);
            state.authInfo = payload;
        },
    },
    getters: {
        loading: state => {
            return state.loading;
        },
        authInfo: state => {
            return state.authInfo;
        },
        authLogged: state => {
            return (typeof state.authInfo.logged !== 'undefined' && state.authInfo.logged === 1);
        },
    },
    actions: {
        showLoading: ({commit, getters}, payload) => {
            commit('showLoading');
        },
        hideLoading: ({commit, getters}, payload) => {
            commit('hideLoading');
        },
        ValidateLogin: ({commit, getters}, payload) => {

            //store.dispatch('coreShowLoading');
            const token = (typeof getters.authInfo !== 'undefined' && typeof getters.authInfo.token !== 'undefined') ? getters.authInfo.token : '';
            let domain = '';

            if (window.opener) {
                const url = new URL(window.location);
                domain = url.searchParams.get("d");
                domain = atob(domain);
            }

            fetch(config.backendUrl + '/auth/validate-login', {
                method: "POST",
                body: JSON.stringify({
                    app: domain,
                }),
                headers: {
                    "Content-Type": "application/json",
                    'Authorization': 'Bearer ' + token
                },
            })
                .then(response => response.json())
                .then(response => {

                    if (typeof response.status !== 'undefined') {

                        // si está logueado
                        if (response.status) {
                            response.data.token = token;
                            commit('authSetInfo', response.data);
                        }
                        else {
                            commit('authSetInfo', {});
                        }

                        // Callback
                        if (typeof payload.callback === 'function') {
                            payload.callback(response.data);
                        }

                        //store.dispatch('coreHideLoading');
                    }
                    else {
                        // deslogueo
                        commit('authSetInfo', {});
                        //store.dispatch('coreHideLoading');
                    }
                })
                .catch(() => {
                    //console.log(e);
                    //store.dispatch('coreHideLoading');
                    if (typeof payload.callback === 'function') {
                        payload.callback(false);
                    }
                    //window.location.href = "/#/login";
                });
        },
    },
    modules: {},
})
