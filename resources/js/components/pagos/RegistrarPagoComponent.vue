<template>
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Registrar boleta de pago</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">
								Registrar pago
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
								<form-wizard back-button-text="Regresar" color="#20a0ff" error-color="#ff4949" finish-button-text="Guardar pago" next-button-text="Siguiente" ref="wizard" step-size="md" shape="circle" subtitle="" title="" v-on:on-complete="onComplete">
									<tab-content title="Seleccionar contador" icon="ti-search" :before-change="validateFirstStep">
										<div class="form-group">
											<select class="form-control" id="contador" name="contador" v-model="pago.contador_id" v-validate="'required'" data-vv-scope="contador" v-on:change="setContador">
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
											<div class="table-responsive">
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
									<tab-content title="Seleccionar consumo" icon="ti-menu" :before-change="validateSecondStep">
										<div class="form-group">
											<select class="form-control" id="consumo" name="consumo" v-model="pago.consumo_id" v-validate="'required'" data-vv-scope="consumo" v-on:change="setConsumo">
												<option value="">Seleccione un consumo</option>
												<option v-bind:value="consumo.id" v-for="(consumo, index) of consumos">
													Consumo número {{ index + 1}}
												</option>
											</select>
											<div class="invalid-feedback ">{{errors.first('consumo.consumo')}}</div>
										</div>
										<div class="bs-callout bs-callout-danger" v-show="consumos.length == 0">
											<h4>El cliente no tiene ningún consumo pendiente de pago</h4>
											<router-link :to="{name: 'registrar.consumo'}">
												<i class="fas fa-list fa-lg"></i>
												¿Desea registrar un consumo?
											</router-link>
										</div>
										<div v-if="consumo_seleccionado != null">
											<div class="table-responsive">
												<table class="table">
													<tbody>
														<tr>
															<th style="width:50%">Fecha de emisión:</th>
															<td>{{consumo_seleccionado.fecha_emision}}</td>
														</tr>
														<tr>
															<th>Período</th>
															<td>{{consumo_seleccionado.mes}}-{{consumo_seleccionado.anio}}</td>
														</tr>
														<tr>
															<th>Monto consumo:</th>
															<td>{{Intl.NumberFormat('en-IN').format(consumo_seleccionado.monto_consumo)}}</td>
														</tr>
														<tr>
															<th>Monto exceso:</th>
															<td>{{Intl.NumberFormat('en-IN').format(consumo_seleccionado.monto_exceso)}}</td>
														</tr>
														<tr>
															<th>Fecha de vencimiento:</th>
															<td>{{consumo_seleccionado.fecha_pago}}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</tab-content>
									<tab-content title="Datos del pago" icon="ti-save" :before-change="validateThirdStep">
										<form autocomplete="off" data-vv-scope="pago" v-if="consumo_seleccionado != null">
											<div class="form-group row">
												<label class="col-sm-4 col-form-label float-right" for="fecha de boleta">Fecha de boleta</label>
												<div class="col-sm-8">
													<input class="form-control" id="fecha de boleta" name="fecha de boleta" type="date" v-model="pago.fecha_deposito" v-validate="'required'">
													<div class="invalid-feedback ">{{errors.first('pago.fecha de boleta')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-4 col-form-label float-right" for="número de boleta">Número de boleta</label>
												<div class="col-sm-8">
													<input class="form-control" id="número de boleta" name="número de boleta" type="number" v-model="pago.numero_deposito" v-validate="'required'">
													<div class="invalid-feedback ">{{errors.first('pago.número de boleta')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-4 col-form-label float-right" for="monto total">Monto total</label>
												<div class="col-sm-8">
													<input class="form-control" id="monto total" name="monto total" type="number" v-model="pago.monto_deposito" v-validate="'required'">
													<div class="invalid-feedback ">{{errors.first('pago.monto total')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-4 col-form-label" for="banco">
													Banco
												</label>
												<div class="col-sm-8">
													<select class="form-control" id="banco" name="banco" type="number" v-model="pago.banco_id" v-validate="'required'">
														<option value=""></option>
														<option v-bind:value="banco.id" v-for="banco of bancos">{{banco.banco}}</option>
														
													</select>
													<div class="invalid-feedback ">{{errors.first('pago.banco')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-4 col-form-label" for="banco">
													Forma de pago
												</label>
												<div class="col-sm-8">
													<select class="form-control" id="forma de pago" name="forma de pago" type="number" v-model="pago.forma_pago_id" v-validate="'required'">
														<option value=""></option>
														<option v-bind:value="forma.id" v-for="forma of formas">{{forma.forma_pago}}</option>
														
													</select>
													<div class="invalid-feedback ">{{errors.first('pago.forma de pago')}}</div>
												</div>
											</div>
										</form>
									</tab-content>
									<tab-content title="Adjuntar boleta" icon="ti-clip">
										<div class="bs-callout bs-callout-primary" v-show="!pago.deposito">
											<h4>Campo opcional</h4>
											Puede dejar vacío este campo
										</div>
										<div class="form-group">
											<label for="deposito">Adjuntar foto de ultima lectura (.jpg)</label>
											<input class="form-control" id="deposito" name="deposito de boleta de depósito" type="file" v-validate="'ext:jpg,png'" v-on:change="getUrlLocalImage">
											<div class="invalid-feedback ">{{errors.first('deposito de boleta de depósito')}}</div>
										</div>
										<div class="text-center">
											<img v-bind:src="pago.deposito" class="img-fluid" alt="Foto de boleta de depósito" v-show="pago.deposito">
										</div>
									</tab-content>
									<tab-content title="Confirmación" icon="ti-check-box">
										<h4 class="d-flex justify-content-between align-items-center mb-3">
											<span class="text-muted">
												Resumen del pago
											</span>
											<span class="badge badge-secondary badge-pill">Boleta {{pago.numero_deposito}}</span>
										</h4>
										<ul class="list-group mb-3">
											<li class="list-group-item d-flex justify-content-between lh-condensed">
												<div>
													<h6 class="my-0">Fecha de boleta</h6>
												</div>
												<span class="text-muted">{{pago.fecha_deposito}}</span>
											</li>
											<li class="list-group-item d-flex justify-content-between lh-condensed">
												<div>
													<h6 class="my-0">Forma de pago</h6>
												</div>
												<span class="text-muted">
													{{pago.forma_pago_id != null ? formas[pago.forma_pago_id-1].forma_pago : ""}}
												</span>
											</li>
											<li class="list-group-item d-flex justify-content-between">
												<span>Total a pagar</span>
												<strong class="h2">Q{{Intl.NumberFormat('en-IN').format(pago.monto_deposito)}}</strong>
											</li>
										</ul>
										<div class="alert alert-danger mt-3" role="alert" v-show="errors_server.length > 0">
											<ul>
												<li v-for="error in errors_server">{{error}}</li>
											</ul>
										</div>
										<div class="bs-callout bs-callout-success" v-show="pago.id !=null">
											<h4>Pago registrado</h4>
											Se registró correctamente el pago de <span class="font-weight-bold">{{cliente.nombres}} {{cliente.apellidos}}</span>, boleta <span class="font-weight-bold">{{pago.numero_deposito}}</span>, monto <span class="font-weight-bold">{{pago.monto_deposito}}</span>
											<br>
											<a title="Imprimir recibo" v-bind:href="`/clientes/${cliente.id}/pagos/${pago.id}/comprobante`" target="_blank">
												<img src="/images/print-icon.png">
											</a>
											<button class="btn btn-primary" v-on:click="nuevoPago">
												<i class="fas fa-tachometer-alt nav-icon"></i>
												Registrar un nuevo pago
											</button>
										</div>
									</tab-content>
								</form-wizard>
							</div>
						</div>
					</div>
					<div v-bind:class="{'col-md-4 offset-md-4': cliente.id == null, 'col-md-3': cliente.id != null}">
						<div class="card card-success card-outline">
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

									<p class="text-muted text-center">
										{{cliente.cui}}
										<br>
										<router-link class="btn btn-primary btn-sm" :to="{name: 'clientes.show', params: {id: cliente.id}}" title="Modificar cliente">
											<i class="fas fa-edit fa-lg"></i>
										</router-link>
									</p>

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
	export default {
		data() {
			return {
				cliente_id: null,
				cliente: {
					id: null
				},
				contadores: [],
				contador_seleccionado: {},
				consumos: [],
				consumo_seleccionado: {},
				bancos: [],
				formas: [],
				pago: {},
				message: {
					show: false
				},
				errors_server: []
			}
		},
		computed: {
			sexo() {
				return this.cliente.sexo == 'M' ? 'male.png' : 'female.png'
			},
			total_contadores() {
				return this.contadores.length
			}
		},
		created() {
			axios.get('/api/bancos?paginate=no')
			.then(response => {
				this.bancos = response.data
			})
			axios.get('/api/formas-de-pago?paginate=no')
			.then(response => {
				this.formas = response.data
			})
		},
		methods: {
			buscarCliente() {
				this.message.show = false
				this.cliente = {}
				this.pago.contador_id = null
				this.contadores = [];
				this.contador_seleccionado = null
				this.consumo_seleccionado = null
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
				this.consumos = [];
				if(this.pago.contador_id == '') {
					this.contador_seleccionado = null
				} else {
					this.contador_seleccionado = this.contadores.find(contador => contador.id == this.pago.contador_id)
					axios.get(`/api/contadores/${this.pago.contador_id}/consumos`)
					.then(response => {
						this.consumos = response.data
					})
				}
			},
			setConsumo() {
				if(this.pago.consumo_id == '') {
					this.consumo_seleccionado = null
				} else {
					this.consumo_seleccionado = this.consumos.find(consumo => consumo.id == this.pago.consumo_id)
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
					this.$validator.validateAll('consumo')
					.then(esValido => {
						resolve(esValido)
					})

				})
			},
			validateThirdStep() {
				return new Promise((resolve, reject) => {
					this.$validator.validateAll('pago')
					.then(esValido => {
						resolve(esValido)
					})

				})
			},
			getUrlLocalImage(e) {
				if(typeof e.target.files[0] != 'undefined') {
					this.pago.deposito = URL.createObjectURL(e.target.files[0])
				}
			},
			onComplete() {
				this.errors_server = [];
				let formData = new FormData();

				formData.append('fecha_deposito', this.pago.fecha_deposito)
				formData.append('numero_deposito', this.pago.numero_deposito)
				formData.append('monto_deposito', this.pago.monto_deposito)
				if(typeof document.getElementById('deposito').files[0] != 'undefined') {
					formData.append('deposito', document.getElementById('deposito').files[0])
				}
				formData.append('contador_id', this.contador_seleccionado.id)

				formData.append('banco_id', this.pago.banco_id)
				formData.append('forma_pago_id', this.pago.forma_pago_id)

				axios.post(`/api/consumos/${this.consumo_seleccionado.id}/pago`, formData, {
					headers:{
						'Accept': 'application/json',
						'Content-Type' : 'multipart/form-data',
					}
				})
				.then(response => {
					Swal.fire({
						title: 'Registro exitoso',
						html: 'Pago registrado correctamente',
						icon: 'success'
					})
					.then(result => {
						this.pago = response.data
					})
				})
				.catch(error => {
					if(error.response.data.exception == 'Illuminate\\Database\\QueryException') {
						Swal.fire({
							title: 'Error',
							html: `El pago que está intentando registrar ya fue guardado con anterioridad`,
							icon: 'error'
						})
					}else if (typeof error.response.data === 'object') {
						this.errors_server = _.flatten(_.toArray(error.response.data.errors))
					} else {
						this.errors_server = [error.response.data]
					}
				})
			},
			nuevoPago() {
				this.cliente_id = null
				this.cliente = {id: null}
				this.contadores = [];
				this.consumos = [];
				this.contador_seleccionado = {}
				this.consumo_seleccionado = {}
				this.pago = {}
				this.message = {show: false}
				this.errors_server = [];
				this.$refs.wizard.reset()
			}
		}
	}
</script>