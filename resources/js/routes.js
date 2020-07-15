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
			component: require('./views/Usuarios_Agregar').default
		},
		{
			path: '/usuarios/lista',
			name: 'lista',
			component: require('./views/Usuarios_Lista').default
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
            path: '/*',
			component: require('./views/404').default
		}
	],
	mode: 'history',
	scrollBehavior() {
		return {x:0, y:0}
	}
})