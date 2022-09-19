import { createRouter, createWebHistory } from 'vue-router';

import home from './admin/pages/adminHome.vue'
import tags from './admin/pages/tags.vue'
import category from './admin/pages/category.vue'
import adminuser from './admin/pages/adminuser.vue'
import login from './admin/pages/login.vue'
import role from './admin/pages/role.vue'
import assineRole from './admin/pages/assineRole.vue'
import createBlog from './admin/pages/createBlog.vue'
import blog from './admin/pages/blog.vue'
import editblog from './admin/pages/editblog.vue'


const routes = [
 
  { 
    path: '/', 
    name: '/home',
    component: home 
  },
  { 
    path: '/tags', 
    name: '/tags',
    component: tags 
  },
  { 
    path: '/category', 
    name: '/category',
    component: category 
  },
  { 
    path: '/blogs', 
    name: '/blogs',
    component: blog 
  },
  { 
    path: '/createBlog', 
    name: '/createBlog',
    component: createBlog 
  },
  { 
    path: '/editblog/:id', 
    name: '/editblog',
    component: editblog 
  },
  { 
    path: '/adminuser', 
    name: '/adminuser',
    component: adminuser 
  },
  { 
    path: '/login', 
    name: 'login',
    component: login 
  },
  { 
    path: '/role', 
    name: '/role',
    component: role 
  },
  { 
    path: '/assineRole', 
    name: '/assineRole',
    component: assineRole 
  },




  {
    path: '/:pathMatch(.*)*',
    name: 'login',
    component: login
 },
 










  // { 
  //   path: '/hooks', 
  //   name: 'hook',
  //   component: hooks 
  // },
  // { 
  //   path: '/methods', 
  //   name: 'medhods',
  //   component: methods 
  // }
    
  ]
  
  const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(process.env.Base_URL),
    routes: routes, // short for `routes: routes`
  
  })

  export default router