import {createRouter,createWebHistory} from 'vue-router';


import dashboard from './components/home/dashboard.vue';
// import profile from './components/admin/profile.vue';
import empty from './components/home/empty.vue';
import error from './components/error/404.vue';

var routes = [

    {path: '/',component: dashboard,meta:{title: 'داشبورد'}},
    {path: '/dashboard',component: dashboard,meta:{title: 'داشبورد'}},
    // {path: '/profile',component: profile,meta:{title: 'پروفایل'}},



    // add here ...



    {path: '/login',component: empty,meta:{title: 'ورود'}},
    { path: "/:pathMatch(.*)*", component: error ,meta:{title: ''}},
    { path: "/404", component: error ,meta:{title: ''}}

];
const router = new createRouter({
    history: createWebHistory(),
    routes
});


router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    next()
});



export default router;
