import Vue from 'vue'
import Router from 'vue-router'


Vue.use(Router)

export default new Router({
	routes: [
		{
			path: '/',
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
			path: '/torneos',
			name: 'torneos',
			component: require('./views/Torneos').default
		},
		{
			path: '/ranking',
			name: 'ranking',
			component: require('./views/Ranking').default
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
            path: '/*',
			component: require('./views/404').default
		},
		
	],
	mode: 'history',
	scrollBehavior() {
		return {x:0, y:0}
	}
})