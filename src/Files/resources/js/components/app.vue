<template>
    <div>
        <app-header :class="{'d-none': !$user.isAuth}"/>
        <app-sidebar :class="{'d-none': !$user.isAuth}"/>

        <div v-if="$user.isAuth">
            <div class="content-wrapper" style="height: fit-content;min-height:100vh !important">
                <section class="content-header" style="height: 5px;">
                </section>

                <section class="content">
                    <div class="container-fluid" style="padding-right: 7.5px !important;">
                        <router-view></router-view>
                    </div>

                </section>
            </div>

            <app-footer/>

            <div class="control-sidebar-bg"></div>
        </div>
        <div v-else class="app-content content m-0">
            <div  style="height: fit-content;min-height:100vh !important">
                <section class="content">
                    <div class="container-fluid">
                        <router-view></router-view>
                    </div>
                </section>
            </div>

            <div class="control-sidebar-bg"></div>
        </div>
    </div>
</template>
