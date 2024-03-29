/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');


window.Vue = require('vue');
import Vue from 'vue';
import Vuetify from 'vuetify'
const vuetify = new Vuetify()
Vue.use(Vuetify);
import es from 'vuetify/es5/locale/es'

//para usar el wysiwyg de vuetify
import { TiptapVuetifyPlugin } from 'tiptap-vuetify'
import 'tiptap-vuetify/dist/main.css'
import 'vuetify/dist/vuetify.min.css'
Vue.use(TiptapVuetifyPlugin, {
    // the next line is important! You need to provide the Vuetify Object to this place.
    vuetify, // same as "vuetify: vuetify"
    // optional, default to 'md' (default vuetify icons before v2.0.0)
    iconsGroup: 'mdi'
})
//end

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('app',                                require('./components/AppComponent.vue').default);
Vue.component('cargar-usuario',                     require('./components/Jugadores/CargarUsuarioComponent.vue').default);
Vue.component('mostrar-usuarios',                   require('./components/Jugadores/ListaUsuariosComponent.vue').default);
Vue.component('editar-usuario',                     require('./components/Jugadores/UsuarioEditarComponent.vue').default);
Vue.component('relaciones-usuario',                 require('./components/Jugadores/UsuarioRelacionesComponent.vue').default);
Vue.component('relaciones-usuario-lista',           require('./components/Jugadores/UsuarioRelacionesListaComponent.vue').default);
Vue.component('configuraciones',                    require('./components/ConfiguracionComponent.vue').default);
Vue.component('crear-cuota',                        require('./components/Jugadores/CrearCuotaComponent.vue').default);
Vue.component('info-cuota-paga',                    require('./components/Jugadores/infoCuotaPagaComponent.vue').default);
Vue.component('pago-cuota',                         require('./components/Jugadores/pagoCuotaComponent.vue').default);
Vue.component('anular-cuotas',                      require('./components/Jugadores/anularCuotasComponent.vue').default);
Vue.component('usuarios-pagos',                     require('./components/Jugadores/UsuariosPagosComponent.vue').default);
Vue.component('crear-torneo',                       require('./components/CrearTorneoComponent.vue').default);
Vue.component('step-nombre-torneo',                 require('./components/CrearTorneo/StepNombreTorneoComponent.vue').default);
Vue.component('step-cateogorias-torneo',            require('./components/CrearTorneo/StepCategoriasTorneoComponent.vue').default);
Vue.component('step-lista-jugadores-torneo',        require('./components/CrearTorneo/StepListaJugadoresTorneoComponent.vue').default);
Vue.component('step-configuracion-puntos-torneo',   require('./components/CrearTorneo/StepConfiguracionPuntosTorneoComponent.vue').default);
Vue.component('agregar-categoria-torneo',           require('./components/CrearTorneo/AgregarCategoriaComponent.vue').default);
Vue.component('tabla-categorias-torneo',            require('./components/CrearTorneo/TablaCategoriasComponent.vue').default);
Vue.component('tabla-jugadores-torneo',             require('./components/CrearTorneo/TablaJugadoresTorneoComponent.vue').default);
Vue.component('importar-jugadores',                 require('./components/CrearTorneo/ImportarJugadoresComponent.vue').default);
Vue.component('agregar-jugador-torneo',             require('./components/CrearTorneo/AgregarJugadorTorneoComponent.vue').default);
Vue.component('buscar-jugador-torneo',              require('./components/CrearTorneo/BuscarJugadoresComponent.vue').default);
Vue.component('crear-fecha',                        require('./components/FechaTorneo/CrearFechaTorneo/CrearFechaTorneoComponent.vue').default);
Vue.component('agregar-jugador-torneo-fecha-modal', require('./components/FechaTorneo/AgregarJugadorTorneoComponent.vue').default);
Vue.component('gestion-fecha',                     require('./components/FechaTorneo/GestionFechaTorneo/GestionFechaJugadoresComponent.vue').default);
Vue.component('gestion-fechas-jugadores',           require('./components/FechaTorneo/GestionFechaTorneo/GestionFechaJugadoresComponent.vue').default);
Vue.component('grupos-categorias',                  require('./components/FechaTorneo/GestionGruposFechaTorneo/GruposCategoriasComponent.vue').default);
Vue.component('partidos-categoria',                 require('./components/FechaTorneo/GestionGruposFechaTorneo/PartidosCategoriaComponent.vue').default);
Vue.component('partidos-fase-grupos',               require('./components/FechaTorneo/GestionGruposFechaTorneo/PartidosFaseGrupoCategoriaComponent.vue').default);
Vue.component('partidos-fase-llaves',               require('./components/FechaTorneo/GestionGruposFechaTorneo/PartidosFaseLlavesCategoriaComponent.vue').default);
Vue.component('partido',                            require('./components/FechaTorneo/GestionGruposFechaTorneo/PartidoComponent.vue').default);
Vue.component('datos-fecha',                        require('./components/FechaTorneo/CrearFechaDatosInicialesComponent.vue').default);
Vue.component('jugadores-fecha',                    require('./components/FechaTorneo/CrearFechaJugadoresComponent.vue').default);
Vue.component('grupos-fecha',                       require('./components/FechaTorneo/CrearFechaGruposComponent.vue').default);
Vue.component('generar-llaves',                     require('./components/FechaTorneo/GenerarLlavesComponent.vue').default);
Vue.component('mostrar-partidos',                   require('./components/FechaTorneo/MostrarPartidosComponent.vue').default);
Vue.component('un-partido',                         require('./components/FechaTorneo/MostrarUnPartidoComponent.vue').default);
Vue.component('snackbar',                           require('./components/SnackBarComponent.vue').default);
Vue.component('resultados-grupos',                  require('./components/FechaTorneo/CrearFechaResultadosGrupos.vue').default);
Vue.component('gestion-torneos',                    require('./components/GestionarTorneos/GestionarTorneosComponent').default);
Vue.component('torneo-fecha',                       require('./components/GestionarTorneos/ResumenTorneoFechaComponent').default);
Vue.component('gestion-ingresos',                   require('./components/Ingresos/GestionIngresosComponent').default);
Vue.component('avisos',                             require('./components/Avisos/AvisosComponent').default);
Vue.component('historial-jugador',                  require('./components/HistorialJugador/HistorialJugador').default);
Vue.component('historial-fecha-jugador',            require('./components/HistorialJugador/HistorialFechaJugador').default);
Vue.component('exportar-historial-jugador',         require('./components/HistorialJugador/ExportarHistorialJugadorComponent').default);
Vue.component('grafico-donut',                      require('./components/GestionarTorneos/GraficoDonut').default);
Vue.component('login',                              require('./components/LoginComponent').default);
Vue.component('modal-login',                        require('./components/Modals/ModalLoginComponent').default);
Vue.component('modal-cambio-contrasena',            require('./components/Modals/ModalCambiarContrasenaComponent').default);
Vue.component('generar-cuotas-masivas',             require('./components/Pagos/GenerarCuotasMasivasComponent').default);
Vue.component('generar-cuota-socio',                require('./components/Pagos/GenerarCuotaSocioComponent').default);
Vue.component('ajustes-cuotas',                     require('./components/Ajustes/AjustesCuotasComponent').default);
Vue.component('spinner',                            require('./components/SpinnerComponent').default);
Vue.component('torneo-lista-jugadores',             require('./components/GestionarTorneos/ListaJugadores').default);
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
