import {createRouter, createWebHistory} from 'vue-router';

import userStore from "./stores/user";

import dashboard from './components/home/dashboard.vue';
import empty from './components/home/empty.vue';
import error from './components/error/404.vue';

import settingIndex from './components/setting/index.vue';

import adminLogin from './components/admin/login.vue';

import roleIndex from './components/role/index.vue';
import roleShow from './components/role/show.vue';
import roleCreate from './components/role/create.vue';

import adminIndex from './components/admin/index.vue';
import adminCreate from './components/admin/create.vue';
import adminShow from './components/admin/show.vue';

var routes = [
    {path: '/login', component: adminLogin, meta: {title: 'Login'}},


    {path: '/', component: dashboard, meta: {title: 'داشبورد'}},
    {path: '/dashboard', component: dashboard, meta: {title: 'داشبورد'}},

    {path: '/admin', component: adminIndex, meta: {title: 'مدیران'}},
    {path: '/admin/create/:id?', component: adminCreate, meta: {title: 'مدیر جدید'}},
    {path: '/admin/:id', component: adminShow, meta: {title: 'مدیر'}},
    {path: '/profile', component: adminProfile, meta: {title: 'پروفایل'}},


    {path: '/setting/:type?', component: settingIndex, meta: {title: 'تنظیمات '}},


    {path: '/role',component: roleIndex,meta:{title: ' لیست نقش ها '}},
    { path: '/role/create/:id?', component: roleCreate, meta: { title: ' نقش جدید ' } },
    { path: '/role/:id', component: roleShow, meta: { title: ' جزئیات ' } },


    {path: '/login', component: empty, meta: {title: 'ورود'}},
    {path: "/:pathMatch(.*)*", component: error, meta: {title: ''}},
    {path: "/404", component: error, meta: {title: ''}}
];
const router = new createRouter({
    history: createWebHistory(),
    routes
});


router.beforeEach(async (to, from, next) => {
    if (!userStore.isAuth) {
        await userStore.checkAuth();
    }
    userStore.stopLoading();

    if (!userStore.isAuth && to.href !== '/login') {
        next({path: '/login'});
    }

    document.title = to.meta.title;
    next();
});


export default router;
