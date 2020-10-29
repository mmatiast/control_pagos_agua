/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import router from './router';
import store from './vuex';
import es from 'vee-validate/dist/locale/es';
import VeeValidate from 'vee-validate';
import { Validator } from 'vee-validate';

router.beforeEach((to, from, next) => {
	document.title = to.meta.title
 	next()
});

Vue.use(VeeValidate, {
	classes: true,
	classNames: {
		valid: '',
		invalid: 'is-invalid'
	}
});

Validator.localize('es', es);
VeeValidate.Validator.extend('validar_nit', {
	getMessage: (campo, argumentos) => {
		return "El campo " + campo + " es incorrecto"
	},
	validate: (valor, argumentos) => {
		if (!valor) {
			return true;
		}

		var nitRegExp = new RegExp('^[0-9]+(-?[0-9kK])?$');

		if (!nitRegExp.test(valor)) {
			return false;
		}

		valor = valor.replace(/-/, '');
		var lastChar = valor.length - 1;
		var number = valor.substring(0, lastChar);
		var expectedCheker = valor.substring(lastChar, lastChar + 1).toLowerCase();

		var factor = number.length + 1;
		var total = 0;

		for (var i = 0; i < number.length; i++) {
			var character = number.substring(i, i + 1);
			var digit = parseInt(character, 10);

			total += (digit * factor);
			factor = factor - 1;
		}

		var modulus = (11 - (total % 11)) % 11;
		var computedChecker = (modulus == 10 ? "k" : modulus.toString());

		return expectedCheker === computedChecker;
	}
})

VeeValidate.Validator.extend('validar_dpi', {
	getMessage: (campo, argumentos) => {
		return "El campo " + campo + " es incorrecto";
	},
	validate: (valor, argumentos) => {
		if (!valor) {
			return true;
		}
		let valorRegExp = /^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/;
		if (!valorRegExp.test(valor)) {
			return false;
		}
		valor = valor.replace(/\s/, '');
		let depto = parseInt(valor.substring(9, 11), 10);
		let muni = parseInt(valor.substring(11, 13));
		let numero = valor.substring(0, 8);
		let verificador = parseInt(valor.substring(8, 9));
		let munisPorDepto = [17,8,16,16,13,14,19,8,24,21,9,30,32,21,8,17,14,5,11,11,7,17];
		if (depto === 0 || muni === 0)
		{
			return false;
		}

		if (depto > munisPorDepto.length)
		{
			return false;
		}

		if (muni > munisPorDepto[depto -1])
		{
			return false;
		}

		let total = 0;

		for (let i = 0; i < numero.length; i++)
		{
			total += numero[i] * (i + 2);
		}
		let modulo = (total % 11);
		return modulo === verificador;
	}
})

const app = new Vue({
	el: '#wrapper',
	router,
	store,
	mounted() {
		this.getUser(this.setUser)
		$('.logout').click(function() {
			this.logout()
		}.bind(this))
	},
	methods: {
		getUser(callback) {
			axios.get('/api/user').then(response => {
				callback(response.data)
			})
			.catch(error => {
				console.log(error)
			})
		},
		setUser(response) {
			this.$store.commit('setUser', response)
		},
		logout() {
			Swal.fire({
				title: 'Cerrar sesión',
				html: `¿Desea cerrar su sesión?`,
				icon: 'warning',
				target: 'page-wrapper',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: '<i class="fas fa-thumbs-up fa-lg mr-2"></i>Si',
				cancelButtonText: '<i class="fas fa-times fa-lg mr-2"></i>No'
			})
			.then(result => {
				if(result.value) {
					axios.post('/logout')
					.then(response => {
						Swal.fire({
							title: 'Información',
							html: 'Su sesión ha sido finalizada exitosamente',
							icon: 'success',
						})
						.then(result  => {
							location.href = '/'
						})
					})
					.catch(error => {
						console.log(error)
					})
				}
			})
		}
	}
});
