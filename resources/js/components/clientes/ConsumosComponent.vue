<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">
							Historial de consumos
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
								Historial de consumos
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
									<router-link class="btn btn-outline-primary btn-block" :to="{name:'registrar.consumo'}">
										<i class="fas fa-tachometer-alt fa-fw fa-lg"></i>
										Registrar consumo
									</router-link>
									<router-link class="btn btn-outline-primary btn-block" :to="{name: 'registrar.pago'}">
										<i class="fas fa-money-bill-alt nav-icon"></i>
										Registrar pago
									</router-link>
									<router-link class="btn btn-outline-primary btn-block" :to="{name: 'clientes.pagos', params: {id: cliente.id}}">
										<i class="fas fa-money-bill-alt fw"></i>
										Historial de pagos
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
											<select class="form-control" id="contador" name="contador" v-model="consumo.contador_id" v-validate="'required'">
												<option value=""></option>
												<option v-bind:value="contador.id" v-for="contador of contadores" v-bind:key="contador.id">
													Correlativo {{contador.id}}
												</option>
											</select>
											<div class="invalid-feedback ">{{errors.first('contador')}}</div>
										</div>
										<div class="col-md-6">
											<label for="año">Año</label>
											<input class="form-control" id="año" name="año" type="number" v-model="consumo.anio" v-validate="'required|digits:4'">
											<div class="invalid-feedback ">{{errors.first('año')}}</div>
										</div>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary">
											<i class="fas fa-search fa-lg mr-2"></i>
											Buscar consumos
										</button>
									</div>
								</form>
								<div class="table-responsive">
									<table class="table table-hover border" v-show="total_consumos">
										<thead class="thead-light">
											<tr>
												<th scope="col">Código</th>
												<th scope="col">Fecha de emisión</th>
												<th scope="col">Mes</th>
												<th scope="col">Año</th>
												<th scope="col">Lectura anterior</th>
												<th scope="col">Lectura actual</th>
												<th scope="col">Consumo</th>
												<th scope="col">Total</th>
												<th scope="col">Vencimiento</th>
												<th scope="col">Estado</th>
												<th scope="col"></th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="consumo of consumos" v-bind:key="consumo.id">
												<th scope="row">{{consumo.id}}</th>
												<td>{{consumo.fecha_emision}}</td>
												<td>{{consumo.mes}}</td>
												<td>{{consumo.anio}}</td>
												<td>{{consumo.ultima_lectura}}</td>
												<td>{{consumo.lectura_actual}}</td>
												<td>
													{{consumo.lectura_actual - consumo.ultima_lectura}}
													m<sup>3</sup>
												</td>
												<td>{{parseInt(consumo.monto_consumo) + parseInt(consumo.monto_exceso == null ? 0 : consumo.monto_exceso)}}</td>
												<td>{{consumo.fecha_pago}}</td>
												<td>{{consumo.estado}}</td>
												<td>
													<a v-bind:href="consumo.foto_lectura_actual == null ? '#' : consumo.foto_lectura_actual.replace('public', '/storage')" target="_blank" v-show="consumo.foto_lectura_actual != null" title="Foto de la lectura actual">
														<i class="fas fa-image fa-lg"></i>
													</a>
													<router-link :to="{name: 'modificar.consumo', params: {id: consumo.id}}">
														<i class="fas fa-edit fa-lg"></i>
													</router-link>
												</td>
											</tr>										
										</tbody>
									</table>
								</div>
								<div class="bs-callout bs-callout-warning" v-show="not_found">
									<h4>Consumos no encontrados</h4>
									Lo sentimos, en la base de datos no existe ningún consumo para el año {{consumo.anio}}
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
				cliente: {},
				contadores: [],
				consumos: [],
				consumo: {},
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
			total_consumos() {
				return this.consumos.length
			}
		},
		methods: {
			buscarConsumos() {
				this.$validator.validate()
				.then(valid => {
					if(valid) {
						this.not_found = false
						axios.get(`/api/clientes/${this.cliente.id}/contadores/${this.consumo.contador_id}/consumos?anio=${this.consumo.anio}`)
						.then(response => {
							this.consumos = response.data
							if (this.total_consumos == 0) {
								this.not_found = true
							}
						})
					}
				})
			}
		}
	}
</script>