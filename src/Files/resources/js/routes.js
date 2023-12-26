import {createRouter,createWebHistory} from 'vue-router';


import dashboard from './components/home/dashboard.vue';
// import profile from './components/admin/profile.vue';
import userStore from "./stores/user";

import settingIndex from './components/setting/index.vue';
import login from "./components/admin/login.vue";
import empty from './components/home/empty.vue';
import error from './components/error/404.vue';

var routes = [
    {path: '/login',component: login,meta:{title: 'login'}},

    {path: '/',component: dashboard,meta:{title: 'داشبورد'}},
    {path: '/dashboard',component: dashboard,meta:{title: 'داشبورد'}},
    // {path: '/profile',component: profile,meta:{title: 'پروفایل'}},



    // add here ...


    {path: '/setting', component: settingIndex, meta: {title: 'تنظیمات'}},

    {path: '/login',component: empty,meta:{title: 'ورود'}},
    { path: "/:pathMatch(.*)*", component: error ,meta:{title: ''}},
    { path: "/404", component: error ,meta:{title: ''}}

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
    setTimeout(() => {
        window.scrollTo(0, 0);
    }, 100)
    next()
});



export default router;
