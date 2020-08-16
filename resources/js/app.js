/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');
import Vuetify from 'vuetify'
Vue.use(Vuetify);
import es from 'vuetify/es5/locale/es'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('app', require('./components/AppComponent.vue').default);
Vue.component('cargar-usuario', require('./components/CargarUsuarioComponent.vue').default);
Vue.component('mostrar-usuarios', require('./components/ListaUsuariosComponent.vue').default);
Vue.component('editar-usuario', require('./components/UsuarioEditarComponent.vue').default);
Vue.component('relaciones-usuario', require('./components/UsuarioRelacionesComponent.vue').default);
Vue.component('relaciones-usuario-lista', require('./components/UsuarioRelacionesListaComponent.vue').default);
Vue.component('configuraciones', require('./components/ConfiguracionComponent.vue').default);
Vue.component('crear-cuota', require('./components/CrearCuotaComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import router from './routes'

const app = new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify({
        lang: {
            locales: { es },
            current: 'es',
          },
    })
    
});
