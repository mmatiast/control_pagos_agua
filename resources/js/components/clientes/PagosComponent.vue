<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">
							Historial de pagos
						</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item">
								<router-link :to="{name: 'clientes.index'}">
									Clientes
								</router-link>
							</li>
							<li class="breadcrumb-item">
								{{cliente.nombres}} {{cliente.apellidos}}
							</li>
							<li class="breadcrumb-item active">
								Historial de pagos
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3">
						<div class="card card-primary card-outline">
							<div class="card-body box-profile">
								<div class="text-center">
									<img class="profile-user-img img-fluid img-circle" v-bind:src="foto_sexo" alt="User profile picture">
								</div>
								<h3 class="profile-username text-center">{{cliente.nombres}} {{cliente.apellidos}}</h3>
								<p class="text-muted text-center">
									{{cliente.cui}}
									<br>
									<router-link :to="{name: 'clientes.show', params: {id: cliente.id}}" title="Modificar cliente">
										<i class="fas fa-edit fa-lg"></i>
									</router-link>
								</p>
								<ul class="list-group list-group-unbordered mb-3">
									<li class="list-group-item">
										<b>Celular</b> <a class="float-right">{{cliente.celular}}</a>
									</li>
									<li class="list-group-item">
										<b>Correo</b> <a class="float-right">{{cliente.correo}}</a>
									</li>
									<li class="list-group-item">
										<b>Contadores</b> <a class="float-right">{{total_contadores}}</a>
									</li>
								</ul>
								<div v-if="total_contadores > 0">
									<router-link class="btn btn-outline-primary btn-block" :to="{name:'registrar.pago'}">
										<i class="fas fa-tachometer-alt fa-fw fa-lg"></i>
										Registrar pago
									</router-link>
									<router-link class="btn btn-outline-primary btn-block" :to="{name: 'registrar.pago'}">
										<i class="fas fa-money-bill-alt nav-icon"></i>
										Registrar pago
									</router-link>
									<router-link class="btn btn-outline-primary btn-block" :to="{name: 'clientes.consumos', params: {id: cliente.id}}">
										<i class="fas fa-money-bill-alt fw"></i>
										Historial de consumos
									</router-link>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-9">
						<div class="card">
							<div class="card-body">
								<div class="bs-callout bs-callout-danger" v-show="total_contadores == 0">
									<h4>No hay contadores registrados</h4>
									<router-link :to="{name: 'clientes.contadores', params: {id: cliente.id}}">
										<i class="fas fa-tachometer-alt fa-lg"></i>
										¿Desea registrar un contador?
									</router-link>
								</div>
								<form v-show="total_contadores > 0" v-on:submit.prevent="buscarConsumos">
									<div class="form-group row">
										<div class="col-md-6">
											<label for="contador">Contador</label>
											<select class="form-control" id="contador" name="contador" v-model="pago.contador_id" v-validate="'required'">
												<option value=""></option>
												<option v-bind:value="contador.id" v-for="contador of contadores" v-bind:key="contador.id">
													Correlativo {{contador.id}}
												</option>
											</select>
											<div class="invalid-feedback ">{{errors.first('contador')}}</div>
										</div>
										<div class="col-md-6">
											<label for="año">Año</label>
											<input class="form-control" id="año" name="año" type="number" v-model="pago.anio" v-validate="'required|digits:4'">
											<div class="invalid-feedback ">{{errors.first('año')}}</div>
										</div>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary">
											<i class="fas fa-search fa-lg mr-2"></i>
											Buscar pagos
										</button>
									</div>
								</form>
								<div class="table-responsive">
									<table class="table table-hover border" v-show="total_pagos">
										<thead class="thead-light">
											<tr>
												<th scope="col">Código</th>
												<th scope="col">Fecha de boleta</th>
												<th scope="col">Número de boleta</th>
												<th scope="col">Monto pagado</th>
												<th scope="col">Forma de pago</th>
												<th scope="col">Banco</th>
												<th scope="col">Período</th>
												<th scope="col">Consumo</th>
												<th scope="col"></th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="pago of pagos" v-bind:key="pago.id">
												<th scope="row">{{pago.id}}</th>
												<td>{{pago.fecha_deposito}}</td>
												<td>{{pago.numero_deposito}}</td>
												<td>{{pago.monto_deposito}}</td>
												<td>{{pago.forma_pago}}</td>
												<td>{{pago.banco}}</td>
												<td>{{pago.mes}}-{{pago.anio}}</td>
												<td>
													{{pago.lectura_actual - pago.ultima_lectura}}
													<b>
														m<sup>3</sup>
													</b>
												</td>
												<td>
													<a v-bind:href="pago.deposito == null ? '#' : pago.deposito.replace('public', '/storage')"target="_blank" v-show="pago.deposito != null" title="Foto de la lectura actual">
														<i class="fas fa-image fa-lg"></i>
													</a>
													<router-link title="Modificar pago" :to="{name: 'modificar.pago', params: {id: pago.id}}">
														<i class="fas fa-edit fa-lg"></i>
													</router-link>
													<a title="Mostrar comprobante" v-bind:href="`/clientes/${cliente.id}/pagos/${pago.id}/comprobante`" target="_blank">
														<i class="fas fa-file-pdf fa-lg"></i>
													</a>
												</td>
											</tr>										
										</tbody>
									</table>
								</div>
								<div class="bs-callout bs-callout-warning" v-show="not_found">
									<h4>Consumos no encontrados</h4>
									Lo sentimos, en la base de datos no existen pagos para el año {{pago.anio}}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
	window.Vue = require('vue');
	import VueFormWizard from 'vue-form-wizard'
	Vue.use(VueFormWizard)
	import 'vue-form-wizard/dist/vue-form-wizard.min.css'
	export default {
		data() {
			return {
				cliente: {},
				contadores: [],
				pagos: [],
				pago: {},
				not_found: false
			}
		},
		mounted() {
			axios.get('/api/clientes/' + this.$route.params.id)
			.then(response => {
				this.cliente = response.data
				axios.get(`/api/clientes/${this.cliente.id}/contadores`)
				.then(response => {
					this.contadores = response.data
				})
			})
		},
		computed: {
			foto_sexo() {
				if (this.cliente.sexo == 'F') {
					return '/images/female.png'
				} else {
					return '/images/male.png'
				}
			},
			sexo() {
				if (this.cliente.sexo == 'M') {
					return 'o'
				} else {
					return 'a'
				}
			},
			total_contadores() {
				return this.contadores.length
			},
			total_pagos() {
				return this.pagos.length
			}
		},
		methods: {
			buscarConsumos() {
				this.$validator.validate()
				.then(valid => {
					if(valid) {
						this.not_found = false
						axios.get(`/api/clientes/${this.cliente.id}/contadores/${this.pago.contador_id}/pagos?anio=${this.pago.anio}`)
						.then(response => {
							this.pagos = response.data
							if (this.total_pagos == 0) {
								this.not_found = true
							}
						})
					}
				})
			}
		}
	}
</script>

<style scoped>
@import 'https://rawgit.com/lykmapipo/themify-icons/master/css/themify-icons.css';
</style>