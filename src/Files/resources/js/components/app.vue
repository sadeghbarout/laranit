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

                        <router-view v-slot="{ Component }">
                            <keep-alive ref="keepAlive" :max="50">
                                <component :is="Component" :key="$route.fullPath" />
                            </keep-alive>
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
