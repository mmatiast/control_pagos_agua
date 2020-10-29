<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Página de inicio</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">Home</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-sm-6 col-md-4">
						<div class="info-box">
							<span class="info-box-icon bg-info elevation-1">
								<i class="fas fa-store-alt"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">
									Usuarios del sistema
								</span>
								<span class="info-box-number">{{ estadistica.usuarios_registrados }}</span>
								<router-link :to="{name: 'users.index'}" v-show="estadistica.usuarios_registrados > 0">
									Mostrar listado
								</router-link>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-md-4">
						<div class="info-box mb-3">
							<span class="info-box-icon bg-warning elevation-1">
								<i class="fas fa-users"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">
									Clientes
								</span>
								<span class="info-box-number">{{ estadistica.clientes_registrados }}</span>
								<router-link :to="{name: 'listado.clientes'}" v-show="estadistica.clientes_registrados > 0">
									Mostrar listado
								</router-link>
							</div>
						</div>
					</div>
					<div class="clearfix hidden-md-up"></div>
					<div class="col-12 col-sm-6 col-md-4">
						<div class="info-box mb-3">
							<span v-bind:class="{'info-box-icon bg-danger elevation-1': estadistica.clientes_en_mora > 0, 'info-box-icon bg-success elevation-1' : estadistica.clientes_en_mora == 0}">
								<i v-bind:class="{'fas fa-thumbs-down': estadistica.clientes_en_mora > 0, 'fas fa-thumbs-up': estadistica.clientes_en_mora == 0}"></i>
							</span>
							<div class="info-box-content">
								<span class="info-box-text">
									Clientes en mora
								</span>
								<span class="info-box-number">{{estadistica.clientes_en_mora}}</span>
								<router-link :to="{name: 'clientes.morosos'}" v-show="estadistica.clientes_en_mora > 0">
									Mostrar listado
								</router-link>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				estadistica: {
					usuarios_registrados: 0,
					clientes_registrados: 0,
					clientes_en_mora: 0
				}
			}
		},
		mounted() {			
			this.obtenerCantidadDeRegistros('users')
			this.obtenerCantidadDeRegistros('clientes')
			axios.get('/api/estadistica/clientes-morosos')
			.then(response => {
				this.estadistica.clientes_en_mora = response.data
			})
		},
		methods: {
			obtenerCantidadDeRegistros(tabla) {
				axios.get('/api/estadistica/conteo/'+ tabla)
				.then(response => {
					if(tabla == 'users') {
						this.estadistica.usuarios_registrados = Intl.NumberFormat('es-GT').format(response.data)
					} else if(tabla == 'clientes') {
						this.estadistica.clientes_registrados = Intl.NumberFormat('es-GT').format(response.data)
					}
				})
			},
			obtenerCantidadDeRegistrosConCondicion(tabla, columna, valor) {
				axios.get(`/api/estadistica/conteo/${tabla}/${columna}/${valor}`)
				.then(response => {
					if(tabla == 'users') {
						this.estadistica.clientes_en_mora = Intl.NumberFormat('es-GT').format(response.data)
					} else if(tabla == 'clientes') {
						this.estadistica.empleados_sin_resolucion = Intl.NumberFormat('es-GT').format(response.data)
					}
				})
			}
		}
	}
</script>