
import Authorpage from '@/pages/Authorpage.vue';
import Bookpage from '@/pages/Bookpage.vue';
import Memberpage from '@/pages/Memberpage.vue';
import { createRouter, createWebHistory } from 'vue-router';
const routes = [
    {
        path: '/',
        name: 'Book',
        component:Bookpage
    },
    {
        path: '/member',
        name: 'Member',
        component:Memberpage
    },
    {
        path: '/author',
        name: 'Author',
        component:Authorpage
    },
    
];
const router = createRouter({
    'history': createWebHistory(),
    routes,
});
export default router;