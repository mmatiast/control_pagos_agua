<template>
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Registrar consumo</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">
								Registrar consumo
							</li>
						</ol>
					</div>
				</div>
			</div>
		</section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="bs-callout bs-callout-danger" v-show="message.show">
							<h4>{{message.title}}</h4>
							<span v-html="message.body"></span>
							<br>
							<router-link :to="{name: 'clientes.create'}" class="btn btn-primary mt-3" v-show="cliente.id ==null">
								<i class="fas fa-user-plus nav-icon"></i>
								Crear cliente
							</router-link>
						</div>
					</div>
					<div class="col-md-9" v-show="cliente.id !=null">
						<div class="card">
							<div class="card-body">
								<form-wizard back-button-text="Regresar" color="#20a0ff" error-color="#ff4949" finish-button-text="Guardar consumo" next-button-text="Siguiente" ref="wizard" step-size="md" shape="circle" subtitle="" title="" v-on:on-complete="onComplete">
									<tab-content title="Seleccionar contador" icon="ti-search" :before-change="validateFirstStep">
										<div class="form-group">
											<select class="form-control" id="contador" name="contador" v-model="consumo.contador_id" v-validate="'required'" data-vv-scope="contador" v-on:change="setContador">
												<option value="">Seleccione un contador</option>
												<option v-bind:value="contador.id" v-for="(contador, index) of contadores">
													Correlativo {{ contador.id}}
												</option>
											</select>
											<div class="invalid-feedback ">{{errors.first('contador.contador')}}</div>
										</div>
										<div class="bs-callout bs-callout-danger" v-show="total_contadores == 0">
											<h4>El cliente no tiene ningún contador registrado</h4>
											<router-link :to="{name: 'clientes.contadores', params: {id: cliente.id}}">
												<i class="fas fa-tachometer-alt fa-lg"></i>
												¿Desea registrar un contador?
											</router-link>
										</div>
										<div v-if="contador_seleccionado != null">
											<div class="table-responsive" v-if="">
												<table class="table">
													<tbody>
														<tr>
															<th style="width:50%">Ubicación:</th>
															<td>{{contador_seleccionado.ubicacion}}</td>
														</tr>
														<tr>
															<th>Fecha de última lectura</th>
															<td>{{contador_seleccionado.fecha_ultima_lectura}}</td>
														</tr>
														<tr>
															<th>Última lectura:</th>
															<td>{{Intl.NumberFormat('en-IN').format(contador_seleccionado.ultima_lectura)}}</td>
														</tr>
														<tr>
															<th>Período:</th>
															<td>{{contador_seleccionado.ultimo_mes}}-{{contador_seleccionado.ultimo_anio}}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</tab-content>
									<tab-content title="Datos del consumo" icon="ti-save" :before-change="validateSecondStep">
										<form autocomplete="off" data-vv-scope="lectura_actual" v-if="contador_seleccionado != null">
											<div class="form-group row">
												<label class="col-sm-4 col-form-label float-right" for="fecha de emisión">Fecha de emisión</label>
												<div class="col-sm-8">
													<input class="form-control" id="fecha de emisión" name="fecha de emisión" type="date" v-model="consumo.fecha_emision" v-validate="'required'">
													<div class="invalid-feedback ">{{errors.first('lectura_actual.fecha de emisión')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-4 col-form-label" for="fecha de lectura actual">
													Fecha de lectura
													<span class="badge badge-secondary">
														{{contador_seleccionado.fecha_ultima_lectura}}
													</span>
												</label>
												<div class="col-sm-8">
													<input class="form-control" id="fecha de lectura actual" name="fecha de lectura actual" type="date" v-model="consumo.fecha_lectura_actual" v-validate="'required'">
													<div class="invalid-feedback ">{{errors.first('lectura_actual.fecha de lectura actual')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-4 col-form-label" for="mes consumo">
													Mes
													<span class="badge badge-secondary">
														{{contador_seleccionado.ultimo_mes}}
													</span>
												</label>
												<div class="col-sm-8">
													<select class="form-control" id="mes consumo" name="mes consumo" type="number" v-model="consumo.mes" v-validate="'required'">
														<option value=""></option>
														<option value="1">1. enero</option>
														<option value="2">2. febrero</option>
														<option value="3">3. marzo</option>
														<option value="4">4. abril</option>
														<option value="5">5. mayo</option>
														<option value="6">6. junio</option>
														<option value="7">7. julio</option>
														<option value="8">8. agosto</option>
														<option value="9">9. septiembre</option>
														<option value="10">10. octubre</option>
														<option value="11">11. noviembre</option>
														<option value="12">12. diciembre</option>
													</select>
													<div class="invalid-feedback ">{{errors.first('lectura_actual.mes consumo')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-4 col-form-label" for="año consumo">
													Año
													<span class="badge badge-secondary">
														{{contador_seleccionado.ultimo_anio}}
													</span>
												</label>
												<div class="col-sm-8">
													<input class="form-control" id="año consumo" name="año consumo" type="number" v-model="consumo.anio" v-validate="'required|digits:4|min_value:2020'">
													<div class="invalid-feedback ">{{errors.first('lectura_actual.año consumo')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-4 col-form-label" for="lectura actual">
													Lectura
													<span class="badge badge-secondary">
														{{Intl.NumberFormat('en-IN').format(contador_seleccionado.ultima_lectura)}}
													</span>
												</label>
												<div class="col-sm-8">
													<input class="form-control" id="lectura actual" name="lectura actual" placeholder="metros cúbicos" type="number" v-model="consumo.lectura_actual" v-validate="'required|min_value:'+contador_seleccionado.ultima_lectura">
													<div class="invalid-feedback ">{{errors.first('lectura_actual.lectura actual')}}</div>
												</div>
											</div>
										</form>
									</tab-content>
									<tab-content title="Cálculo" icon="ti-money">
										<div v-if="contador_seleccionado != null">
											<p>
												Lectura anterior: {{Intl.NumberFormat('en-IN').format(contador_seleccionado.ultima_lectura)}} *
												Lectura actual: {{Intl.NumberFormat('en-IN').format(consumo.lectura_actual)}} *
												<strong>Consumo del mes: {{consumo_del_mes}}</strong>
											</p>
											<div class="table-responsive">
												<table class="table table-bordered">
													<thead class="thead-light">
														<tr>
															<th style="width: 50%">Concepto</th>
															<th style="width: 25%">Metros cúbicos</th>
															<th style="width: 25%">Total a pagar</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Cuota fija</td>
															<td>15</td>
															<td>Q.{{Intl.NumberFormat('en-IN').format(cuota_fija)}}</td>
														</tr>
														<tr>
															<td>Exceso</td>
															<td>{{exceso}}</td>
															<td>Q.{{Intl.NumberFormat('en-IN').format(monto_exceso)}}</td>
														</tr>
														<tr>
															<td align="right">
																<strong>TOTALES</strong>
															</td>
															<td>{{consumo_del_mes}}</td>
															<td>Q.{{Intl.NumberFormat('en-IN').format(monto_total)}}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</tab-content>
									<tab-content title="Evidencia" icon="ti-clip">
										<div class="bs-callout bs-callout-primary" v-show="!consumo.foto">
											<h4>Campo opcional</h4>
											Puede dejar vacío este campo
										</div>
										<div class="form-group">
											<label for="foto de última lectura">Adjuntar foto de ultima lectura (.jpg)</label>
											<input class="form-control" id="foto_lectura_actual" name="foto de última lectura" type="file" v-validate="'ext:jpg,png'" v-on:change="getUrlLocalImage">
											<div class="invalid-feedback ">{{errors.first('foto de última lectura')}}</div>
										</div>
										<div class="text-center">
											<img v-bind:src="consumo.foto" class="img-fluid" alt="Foto de lectura actual" v-show="consumo.foto">
										</div>
									</tab-content>
									<tab-content title="Confirmación" icon="ti-check-box" :before-change="validateThirdStep">
										<h4 class="d-flex justify-content-between align-items-center mb-3">
											<span class="text-muted">
												Resumen del consumo
											</span>
											<span class="badge badge-secondary badge-pill">Mes {{consumo.mes}}-{{consumo.anio}}</span>
										</h4>
										<ul class="list-group mb-3">
											<li class="list-group-item d-flex justify-content-between lh-condensed">
												<div>
													<h6 class="my-0">Cuota fija</h6>
													<small class="text-muted">
														15 m<sup>3</sup>
													</small>
												</div>
												<span class="text-muted">{{Intl.NumberFormat('en-IN').format(cuota_fija)}}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between lh-condensed">
												<div>
													<h6 class="my-0">Exceso</h6>
													<small class="text-muted">{{Intl.NumberFormat('en-IN').format(exceso)}} m<sup>3</sup></small>
												</div>
												<span class="text-muted">Q{{Intl.NumberFormat('en-IN').format(monto_exceso)}}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between">
												<span>Total a pagar</span>
												<strong class="h2">Q{{monto_total}}</strong>
											</li>
										</ul>
										<form autocomplete="off" class="card p-2" data-vv-scope="fecha_pago">
											<div class="form-group">
												<label for="fecha de vencimiento de pago">
													Fecha de vencimiento de pago de este consumo
												</label>
												<input class="form-control" id="fecha de vencimiento de pago" name="fecha de vencimiento de pago" type="date" v-model="consumo.fecha_pago" v-validate="'required'">
												<div class="invalid-feedback ">{{errors.first('fecha_pago.fecha de vencimiento de pago')}}</div>
											</div>
										</form>
										<div class="alert alert-danger mt-3" role="alert" v-show="errors_server.length > 0">
											<ul>
												<li v-for="error in errors_server">{{error}}</li>
											</ul>
										</div>
										<div class="bs-callout bs-callout-success" v-show="consumo.id !=null">
											<h4>Consumo registrado</h4>
											Se registró correctamente el consumo para <span class="font-weight-bold">{{cliente.nombres}} {{cliente.apellidos}}</span>, correspondiente al mes <span class="font-weight-bold">{{consumo.mes}}-{{consumo.anio}}</span>
											<br>
											<button class="btn btn-primary" v-on:click="nuevoConsumo">
												<i class="fas fa-tachometer-alt nav-icon"></i>
												Registrar un nuevo consumo
											</button>
										</div>
									</tab-content>
								</form-wizard>
							</div>
						</div>
					</div>
					<div v-bind:class="{'col-md-4 offset-md-4': cliente.id == null, 'col-md-3': cliente.id != null}">
						<div class="card card-primary card-outline">
							<div class="card-body box-profile">
								<form autocomplete="off" v-on:submit.prevent="buscarCliente">
									<div class="input-group">
										<input class="form-control" id="código del cliente" name="código del cliente" placeholder="Introdúzca el código del cliente" type="text" v-model="cliente_id" v-validate="'required'">
										<div class="invalid-feedback ">{{errors.first('código del cliente')}}</div>
										<div class="input-group-append">
											<button class="btn btn-primary" type="submit">
												<i class="fas fa-search fa-lg mr-2"></i>
											</button>
										</div>
									</div>
								</form>
								<div v-if="cliente.id !=null">
									<div class="text-center">
										<img class="profile-user-img img-fluid img-circle" v-bind:src="'/images/' + sexo" alt="User profile picture">
									</div>

									<h3 class="profile-username text-center">{{cliente.nombres}} {{cliente.apellidos}}</h3>

									<p class="text-muted text-center">{{cliente.cui}}</p>

									<ul class="list-group list-group-unbordered mb-3">
										<li class="list-group-item">
											<b>Contadores registrados</b>
											<a class="float-right">{{total_contadores}}</a>
										</li>
										<li class="list-group-item">
											<b>Celular</b>
											<a class="float-right">{{cliente.celular}}</a>
										</li>
										<li class="list-group-item text-center">
											{{cliente.correo}}
										</li>
									</ul>
									<router-link class="btn btn-outline-primary btn-block" :to="{name: 'clientes.show', params: {id: cliente.id}}">
										<i class="fas fa-edit fa-lg"></i>
										Modificar cliente
									</router-link>
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
				cliente_id: null,
				cliente: {
					id: null
				},
				contadores: [],
				contador_seleccionado: {},
				consumo: {},
				message: {
					show: false
				},
				errors_server: []
			}
		},
		mounted() {
			this.consumo.fecha_emision = new Date().toISOString().slice(0,10)
			console.log(this.consumo.fecha_emision)
		},
		computed: {
			sexo() {
				return this.cliente.sexo == 'M' ? 'male.png' : 'female.png'
			},
			total_contadores() {
				return this.contadores.length
			},
			cuota_fija() {
				return 80
			},
			consumo_del_mes() {
				return this.contador_seleccionado != null ? this.consumo.lectura_actual - this.contador_seleccionado.ultima_lectura : 0
			},
			exceso() {
				if(this.consumo_del_mes > 15) {
					return this.consumo_del_mes - 15
				} else {
					return 0
				}
			},
			monto_exceso() {
				return this.exceso * 20
			},
			monto_total() {
				return this.cuota_fija + this.monto_exceso
			}
		},
		methods: {
			buscarCliente() {
				this.message.show = false
				this.cliente = {}
				this.consumo.contador_id = null
				this.contadores = [];
				this.contador_seleccionado = null
				this.$validator.validateAll('cliente')
				.then(esValido => {
					if (esValido) {
						axios.get('/api/clientes/' + this.cliente_id)
						.then(response => {
							this.cliente = response.data
							this.buscarContadores(this.cliente.id)
						})
						.catch(error => {
							if(error.response.status == 404) {
								this.message.show = true
								this.message.title = 'Cliente no encontrado'
								this.message.body = `
								El cliente con código <span class="font-weight-bold">${this.cliente_id}</span> no existe en la base de datos`
							}
							this.cliente.id = null
						})
					} else {
						this.cliente.id = null
					}
				})
			},
			buscarContadores(cliente_id) {
				axios.get(`/api/clientes/${this.cliente_id}/contadores`)
				.then(response => {
					this.contadores = response.data
				})
				.catch(error => {

				})
			},
			setContador() {
				if(this.consumo.contador_id == '') {
					this.contador_seleccionado = null
				} else {
					this.contador_seleccionado = this.contadores.find(contador => contador.id == this.consumo.contador_id)
				}
			},
			validateFirstStep() {
				this.message.show = false
				return new Promise((resolve, reject) => {
					this.$validator.validateAll('contador')
					.then(esValido => {
						resolve(esValido)
					})
				})
			},
			validateSecondStep() {
				return new Promise((resolve, reject) => {
					this.$validator.validateAll('lectura_actual')
					.then(esValido => {
						resolve(esValido)
					})

				})
			},
			validateThirdStep() {
				return new Promise((resolve, reject) => {
					this.$validator.validateAll('fecha_pago')
					.then(esValido => {
						resolve(esValido)
					})

				})
			},
			getUrlLocalImage(e) {
				if(typeof e.target.files[0] != 'undefined') {
					this.consumo.foto = URL.createObjectURL(e.target.files[0])
				}
			},
			onComplete() {
				this.errors_server = [];
				let formData = new FormData();
				formData.append('fecha_emision', this.consumo.fecha_emision)
				formData.append('mes', this.consumo.mes)
				formData.append('anio', this.consumo.anio)
				formData.append('fecha_ultima_lectura', this.contador_seleccionado.fecha_ultima_lectura)
				formData.append('ultima_lectura', this.contador_seleccionado.ultima_lectura)
				formData.append('fecha_lectura_actual', this.consumo.fecha_lectura_actual)
				formData.append('lectura_actual', this.consumo.lectura_actual)
				if(typeof document.getElementById('foto_lectura_actual').files[0] != 'undefined') {
					formData.append('foto_lectura_actual', document.getElementById('foto_lectura_actual').files[0])
				}
				formData.append('consumo', this.consumo_del_mes)
				formData.append('monto_consumo', this.cuota_fija)
				formData.append('exceso', this.exceso)
				formData.append('monto_exceso', this.monto_exceso)
				formData.append('fecha_pago', this.consumo.fecha_pago)
				formData.append('contador_id', this.consumo.contador_id)

				axios.post(`/api/contadores/${this.contador_seleccionado.id}/consumo`, formData, {
					headers:{
						'Accept': 'application/json',
						'Content-Type' : 'multipart/form-data',
					}
				})
				.then(response => {
					Swal.fire({
						title: 'Registro exitoso',
						html: 'Consumo registrado correctamente',
						icon: 'success'
					})
					.then(result => {
						this.consumo = response.data
					})
				})
				.catch(error => {
					if(error.response.data.exception == 'Illuminate\\Database\\QueryException') {
						Swal.fire({
							title: 'Error',
							html: `El consumo que está intentando registrar ya fue guardado con anterioridad`,
							icon: 'error'
						})
					}else if (typeof error.response.data === 'object') {
						this.errors_server = _.flatten(_.toArray(error.response.data.errors))
					} else {
						this.errors_server = [error.response.data]
					}
				})
			},
			nuevoConsumo() {
				this.cliente_id = null
				this.cliente = {id: null}
				this.contadores = [];
				this.contador_seleccionado = {}
				this.consumo = {}
				this.message = {show: false}
				this.errors_server = [];
				this.$refs.wizard.reset()
			}
		}
	}
</script>
<style scoped>
	@import 'https://rawgit.com/lykmapipo/themify-icons/master/css/themify-icons.css';
</style>