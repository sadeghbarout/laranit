/**
 * Created by sadegh on 12/06/2018.
 */
import VueRouter from 'vue-router';

var routes1 = [

    {path: '/',component: require('./components/home/dashboard.vue'),meta:{title: 'داشبورد'}},
    {path: '/dashboard',component: require('./components/home/dashboard.vue'),meta:{title: 'داشبورد'}},
    // {path: '/profile',component: require('./components/admin/admin/profile.vue'),meta:{title: 'پروفایل'}},



    // add here ...



    {path: '/login',component: require('./components/home/empty.vue'),meta:{title: 'ورود'}},
    { path: "*", component: require('./components/error/404.vue') ,meta:{title: ''}},
    { path: "/404", component: require('./components/error/404.vue') ,meta:{title: ''}}

];
const router = new VueRouter({
    mode: 'history',
    routes: routes1
})


router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    next()
});



export default router;
