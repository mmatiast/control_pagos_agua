import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
	mode: 'history',
	scrollBehavior() {
		return {x:0, y:0}
	},
	linkActiveClass: 'active',
 	routes: [
 	{
 		path: '/home',
 		name: 'home',
 		component: require('./components/HomeComponent').default,
 		meta: { title: 'Página de inicio'}
 	},
 	{
 		path: '/profile',
 		name: 'profile',
 		component: require('./components/users/ProfileComponent').default,
 		meta: { title: 'Perfil de usuario'}
 	},
 	{
 		path: '/users',
 		component: require('./components/RouterViewComponent').default,
 		children: [
 		{
 			path: '',
 			name: 'users.index',
 			component: require('./components/users/IndexComponent').default,
 			meta: { title: 'Listado de usuarios' }
 		},
 		{
 			path: ':id/edit',
 			name: 'users.edit',
 			component: require('./components/users/EditComponent').default,
 			meta: { title: 'Modificar usuario' }
 		}
 		]
 	},
 	{
 		path: '/roles/',
 		component: require('./components/RouterViewComponent').default,
 		children: [
 		{
 			path: '',
 			name: 'roles.index',
 			component: require('./components/roles/IndexComponent').default,
 			meta: { title: 'Listado de roles' }
 		},
 		{
 			path: ':id/show',
 			name: 'roles.show',
 			component: require('./components/roles/ShowComponent').default,
 			meta: { title: 'Detalle de rol' }
 		},
 		{
 			path: ':id/edit',
 			name: 'roles.edit',
 			component: require('./components/roles/EditComponent').default,
 			meta: { title: 'Modificar rol' }
 		},
 		]
 	},
 	{
 		path: '/bancos/',
 		component: require('./components/RouterViewComponent').default,
 		children: [
 		{
 			path: '',
 			name: 'bancos.index',
 			component: require('./components/bancos/IndexComponent').default,
 			meta: { title: 'Listado de bancos' }
 		},
 		{
 			path: ':id/show',
 			name: 'bancos.show',
 			component: require('./components/bancos/ShowComponent').default,
 			meta: { title: 'Detalle de banco' }
 		},
 		{
 			path: ':id/edit',
 			name: 'bancos.edit',
 			component: require('./components/bancos/EditComponent').default,
 			meta: { title: 'Modificar banco' }
 		},
 		]
 	},
 	{
 		path: '/formas-de-pago/',
 		component: require('./components/RouterViewComponent').default,
 		children: [
 		{
 			path: '',
 			name: 'formapagos.index',
 			component: require('./components/formapagos/IndexComponent').default,
 			meta: { title: 'Listado de formas de pago ' }
 		},
 		{
 			path: ':id/show',
 			name: 'formapagos.show',
 			component: require('./components/formapagos/ShowComponent').default,
 			meta: { title: 'Detalle de forma de pago' }
 		},
 		{
 			path: ':id/edit',
 			name: 'formapagos.edit',
 			component: require('./components/formapagos/EditComponent').default,
 			meta: { title: 'Modificar forma de pago' }
 		},
 		]
 	},
 	{
 		path: '/clientes/',
 		component: require('./components/RouterViewComponent').default,
 		children: [
 		{
 			path: 'index',
 			name: 'clientes.index',
 			component: require('./components/clientes/IndexComponent').default,
 			meta: { title: 'Clientes' }
 		},
 		{
 			path: 'create',
 			name: 'clientes.create',
 			component: require('./components/clientes/CreateComponent').default,
 			meta: { title: 'Crear cliente' }
 		},
 		{
 			path: ':id',
 			name: 'clientes.show',
 			component: require('./components/clientes/ShowComponent').default,
 			meta: { title: 'Detalle de cliente' }
 		},
 		{
 			path: ':id/contadores',
 			name: 'clientes.contadores',
 			component: require('./components/clientes/ContadoresComponent').default,
 			meta: { title: 'Seleccionar contador' }
 		},
 		{
 			path: ':id/consumos',
 			name: 'clientes.consumos',
 			component: require('./components/clientes/ConsumosComponent').default,
 			meta: { title: 'Registrar consumo' }
 		},
 		{
 			path: ':id/pagos',
 			name: 'clientes.pagos',
 			component: require('./components/clientes/PagosComponent').default,
 			meta: { title: 'Registrar pago' }
 		}
 		]
 	},
 	{
 		path: '/registrar-consumo',
 		name: 'registrar.consumo',
 		component: require('./components/consumos/RegistrarConsumoComponent').default,
 		meta: { title: 'Registrar consumo'}
 	},
 	{
 		path: '/consumo/:id/edit',
 		name: 'modificar.consumo',
 		component: require('./components/consumos/ModificarConsumoComponent').default,
 		meta: { title: 'Modificar consumo'}
 	},
 	{
 		path: '/registrar-pago',
 		name: 'registrar.pago',
 		component: require('./components/pagos/RegistrarPagoComponent').default,
 		meta: { title: 'Registrar pago'}
 	},
 	{
 		path: '/pago/:id/edit',
 		name: 'modificar.pago',
 		component: require('./components/pagos/ModificarPagoComponent').default,
 		meta: { title: 'Modificar pago'}
 	},
 	{
 		path: '/clientes-morosos',
 		name: 'clientes.morosos',
 		component: require('./components/clientes/MorososComponent').default,
 		meta: { title: 'Clientes morosos'}
 	},
 	{
 		path: '/litado-clientes',
 		name: 'listado.clientes',
 		component: require('./components/clientes/ListadoComponent').default,
 		meta: { title: 'Listado de clientes'}
 	}
 	]
 })
