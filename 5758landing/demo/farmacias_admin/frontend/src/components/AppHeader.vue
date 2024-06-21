<template>
    <CHeader position="sticky" class="mb-4">
        <CContainer fluid>
            <CHeaderToggler class="ps-1" @click="$store.commit('toggleSidebar')">
                <CIcon icon="cil-menu" size="lg"/>
            </CHeaderToggler>
            <CHeaderBrand class="mx-auto d-lg-none" to="/">
                <img src="../assets/images/logo-dark.png" style="max-width: 100px; margin: auto;">
            </CHeaderBrand>
            <CHeaderNav class="d-none d-md-flex me-auto">
                <!--<CNavItem>
                    <CNavLink href="/dashboard"> Dashboard</CNavLink>
                </CNavItem>
                <CNavItem>
                    <CNavLink href="#">Users</CNavLink>
                </CNavItem>
                <CNavItem>
                    <CNavLink href="#">Settings</CNavLink>
                </CNavItem>-->
            </CHeaderNav>
            <CHeaderNav>
                <!--<CNavItem>
                    <CNavLink href="#">
                        <CIcon class="mx-2" icon="cil-bell" size="lg"/>
                    </CNavLink>
                </CNavItem>
                <CNavItem>
                    <CNavLink href="#">
                        <CIcon class="mx-2" icon="cil-list" size="lg"/>
                    </CNavLink>
                </CNavItem>
                <CNavItem>
                    <CNavLink href="#">
                        <CIcon class="mx-2" icon="cil-envelope-open" size="lg"/>
                    </CNavLink>
                </CNavItem>-->
                <button class="btn btn-secondary" @click="logout">
                    <i class="fas fa-lock"/>
                    <span class="d-none d-sm-inline-block ms-2">Cerrar sesión</span>
                </button>
            </CHeaderNav>
        </CContainer>
        <CHeaderDivider/>
        <CContainer fluid>
            <AppBreadcrumb/>
        </CContainer>
    </CHeader>
</template>

<script>
import AppBreadcrumb from './AppBreadcrumb'
import AppHeaderDropdownAccnt from './AppHeaderDropdownAccnt'
import {logo} from '@/assets/brand/logo'
import {mapMutations} from "vuex";
import toolbox from "@/toolbox";

export default {
    name: 'AppHeader',
    components: {
        AppBreadcrumb,
        AppHeaderDropdownAccnt,
    },
    setup() {
        return {
            logo,
        }
    },
    methods: {
        ...mapMutations(["authSetInfo"]),
        logout() {

            const self = this;
            toolbox.doAjax('POST', 'auth/logout', {},
                function (response) {
                    self.authSetInfo({});
                    self.$router.push('/login');
                },
                function (response) {
                    toolbox.alert(response.msg, 'danger');
                })

            //loginClose

        }
    }
}
</script>
