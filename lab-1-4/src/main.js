import Vue from 'vue'
import App from './App.vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import VueLodash from 'vue-lodash'
import lodash from 'lodash'
import axios from 'axios';
import VueAxios from 'vue-axios';
// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
Vue.config.productionTip = false
Vue.use(VueLodash, { name: 'custom' , lodash: lodash })
Vue.use(VueAxios, axios);
new Vue({
  render: h => h(App),
}).$mount('#app')
