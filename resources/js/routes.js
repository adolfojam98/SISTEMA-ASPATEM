import Vue from 'vue'
import Router from 'vue-router'


Vue.use(Router)

export default new Router({
	routes: [
		{
			path: '/',
			name: 'base',
			component: require('./views/Home').default
		},
        {
			path: '/home',
			name: 'home',
			component: require('./views/Home').default
		},
		{
			path: '/usuarios/agregar',
			name: 'agregar',
			component: require('./views/UsuariosAgregar').default
		},
		{
			path: '/usuarios/lista',
			name: 'lista',
			component: require('./views/UsuariosLista').default
		},
		{
			path: '/torneos/crear',
			name: 'crear-torneos',
			component: require('./views/TorneoCrear').default
		},
		{
			path: '/ver/ingresos',
			name: 'ingresos',
			component: require('./views/Ingresos').default
		},
		
		{
			path: '/usuarios/pagos',
			name: 'pagos',
			component: require('./views/UsuariosPagos').default
		},

		{
			path: '/configuracion',
			name: 'configuracion',
			component: require('./views/Configuracion').default
		},

		{
			path: '/torneos/crearfecha',
			name: 'crear-fecha',
			component: require('./views/TorneoCrearFecha').default
		},
		{
			path: '/torneos/gestion',
			name: 'gestion-torneos',
			component: require('./views/TorneoGestion').default
		},

        {
            path: '/resumen/torneo/fecha/:id',
            name: 'resumen-torneo-fecha',
            component: require('../js/components/GestionarTorneos/ResumenTorneoFechaComponent').default
        },

        {
            path: '/avisos',
            name: 'avisos',
            component: require('../js/views/Avisos').default
        },

        {
            path: '/historial-jugador',
            name: 'historial-jugador',
            component: require('../js/views/HistorialJugador').default
        },
		
		{
            path: '/*',
			component: require('./views/404').default
		},
		
	],
	mode: 'history',
	scrollBehavior() {
		return {x:0, y:0}
	}
})