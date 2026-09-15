<template>
    <div>
        <appHeader :class="{'d-none': !$user.isAuth}"/>
        <appSidebar :class="{'d-none': !$user.isAuth}"/>
        <div v-if="$user.isAuth">

            <div class="app-content content" style="min-height: calc(100vh - 49px);">
                <div class="content-overlay"></div>
                <div class="header-navbar-shadow"></div>
                <div class="content-wrapper">
                    <div class="content-body">

                        <router-view v-slot="{ Component, route }">
                            <keep-alive :max="1">
                                <component
                                    :is="Component"
                                    :key="route.meta.keepAlive ? route.fullPath : route.name"
                                    v-if="route.meta.keepAlive"
                                />
                            </keep-alive>
                            <component
                                :is="Component"
                                :key="!route.meta.keepAlive ? route.fullPath : route.name"
                                v-if="!route.meta.keepAlive"
                            />
                        </router-view>

                    </div>
                </div>
            </div>

            <div class="sidenav-overlay"></div>
            <div class="drag-target"></div>

            <appFooter/>

            <div class="control-sidebar-bg"></div>
        </div>
        <div v-else class="app-content content m-0" id="vueAppDiv">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            noCacheRoutes: [
                '/request/citizen/create'
            ],
        }
    },
    computed: {
        getKeyForRoute() {
            if (this.noCacheRoutes.includes(this.$route.path)) {
                return Date.now();
            }
            return this.$route.fullPath;
        }
    }
};
</script>
