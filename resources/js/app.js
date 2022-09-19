require('./bootstrap');


import { createApp } from 'vue';
import router from './router';
import mainapp from './components/mainapp.vue';
import common from './common.js';
import store from './store.js';

import ViewUIPlus from 'view-ui-plus';
import 'view-ui-plus/dist/styles/viewuiplus.css';
import CKEditor from '@ckeditor/ckeditor5-vue';




const app = createApp({
    components: {
        mainapp,
    }
})

.mixin(common)
.use(store)
.use(router)
.use(ViewUIPlus)
.use(CKEditor)
.mount('#app')

// window.Vue = require('vue')
// import Router  from './router';

// Vue.component('mainapp',require('./components/mainapp.vue').default)
// const app = new Vue ({
//     el: '#app',
//     Router
// })

// import {CreateRouter} from 'vue-router';
//import mainapp from './components/mainapp.vue'
// let app=createApp({
// })
// app.component('mainapp', require('./components/mainapp.vue').default);
// app.use(CreateRouter)
// app.mount("#app")