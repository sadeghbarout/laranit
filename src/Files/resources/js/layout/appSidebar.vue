<template>
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="/">
                        <div></div>
                        <h2 class="brand-text mb-0">سامانه تست</h2>
                    </a>
                </li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item"><router-link to="/dashboard"><i class="fas fa-home"></i><span class="menu-title">داشبورد</span></router-link></li>
                <template v-if="adminHasPermission(PERM_ADMIN_LIST_SHOW) || adminHasPermission(PERM_ROLE_LIST_SHOW)">
                    <li class="navigation-header"><span>لیست</span></li>
                </template>
                <template v-if="adminHasPermission(PERM_ADMIN_LIST_SHOW)">
                    <li class="nav-item"><router-link to="/admin"><i class="fas fa-key"></i><span class="menu-title">مدیران</span></router-link></li>
                </template>
                <template v-if="adminHasPermission(PERM_ROLE_LIST_SHOW)">
                    <li class="nav-item"><router-link to="/role"><i class="fas fa-user-tag"></i><span class="menu-title">نقش ها و دسترسی ها</span></router-link></li>
                </template>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        $(document).ready(function(){
            $('.dropdown-toggle').dropdown()
        });
    }
}
</script>
