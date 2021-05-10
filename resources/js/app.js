/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');
import Vue from 'vue';
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
Vue.component('info-cuota-paga', require('./components/infoCuotaPagaComponent.vue').default);
Vue.component('pago-cuota', require('./components/pagoCuotaComponent.vue').default);
Vue.component('usuarios-pagos', require('./components/UsuariosPagosComponent.vue').default);
Vue.component('crear-torneo',require('./components/CrearTorneoComponent.vue').default);
Vue.component('importar-jugadores',require('./components/ImportarJugadoresComponent.vue').default);
Vue.component('crear-fecha',require('./components/FechaTorneo/CrearFechaComponent.vue').default);
Vue.component('datos-fecha',require('./components/FechaTorneo/CrearFechaDatosInicialesComponent.vue').default);
Vue.component('jugadores-fecha',require('./components/FechaTorneo/CrearFechaJugadoresComponent.vue').default);
Vue.component('grupos-fecha',require('./components/FechaTorneo/CrearFechaGruposComponent.vue').default);
Vue.component('generar-llaves',require('./components/FechaTorneo/GenerarLlavesComponent.vue').default);
Vue.component('mostrar-partidos',require('./components/FechaTorneo/MostrarPartidosComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import store from './store'
import router from './routes'

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify: new Vuetify({
        lang: {
            locales: { es },
            current: 'es',
          },
    })
    
});
