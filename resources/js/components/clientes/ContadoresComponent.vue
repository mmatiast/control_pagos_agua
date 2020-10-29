<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Contadores del cliente número {{String(cliente.id).padStart(6, '000000')}}</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item">
								<router-link :to="{name: 'clientes.index'}">
									Clientes
								</router-link>
							</li>
							<li class="breadcrumb-item active">
								{{cliente.nombres}} {{cliente.apellidos}}
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
									<router-link class="btn btn-outline-primary btn-block" :to="{name: 'clientes.consumos', params: {id: cliente.id}}">
										<i class="fas fa-tachometer-alt fw"></i>
										Historial de consumos
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
								<button class="btn btn-outline-secondary mb-3" v-on:click="createContador">
									<i class="fas fa-stopwatch-20 fa-fw fa-lg"></i>
									Crear contador
								</button>
								<div class="alert alert-danger" role="alert" v-show="total_contadores == 0">
									No hay contadores registrados
								</div>
								<table class="table table-hover border" v-show="total_contadores > 0">
									<thead class="thead-light">
										<tr>
											<th scope="col">Correlativo</th>
											<th scope="col">Ubicación</th>
											<th scope="col">Última lectura</th>
											<th scope="col">Fecha última lectura</th>
											<th scope="col">Último período de consumo</th>
											<th scope="col"></th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(contador, index) of contadores">
											<th scope="row">{{contador.id}}</th>
											<td>{{contador.ubicacion}}</td>
											<td>
												{{Intl.NumberFormat('en-IN').format(contador.ultima_lectura)}}
												m<sup>3</sup>
											</td>
											<td>{{contador.fecha_ultima_lectura}}</td>
											<td>{{contador.ultimo_mes}}-{{contador.ultimo_anio}}</td>
											<td>
												<button class="btn btn-primary btn-sm stretched-link" v-on:click="editContador(index)" title="Modificar contador">
													<i class="fas fa-edit"></i>
													Modificar
												</button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="contadorModal" tabindex="-1" aria-labelledby="contadorModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="contadorModalLabel">
							<i v-bind:class="contador_temp.icon"></i>
							{{contador_temp.title}}
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form autocomplete="off" v-on:submit.prevent="validarFormularioContador" data-vv-scope="contador">
						<div class="modal-body">
							<div class="alert alert-danger mt-3" role="alert" v-show="errors_server.length > 0">
								<ul>
									<li v-for="error in errors_server">{{error}}</li>
								</ul>
							</div>
							<div class="form-group">
								<label for="última lectura">Última lectura en metros cúbicos (m<sup>3</sup>)</label> <span class="text-danger">*</span>
								<input class="form-control" id="última lectura" name="última lectura" type="number" v-model="contador_temp.ultima_lectura" v-validate="'required|integer'">
								<div class="invalid-feedback ">{{errors.first('contador.última lectura')}}</div>
							</div>
							<div class="form-group">
								<label for="fecha de última lectura">Fecha de última lectura</label> <span class="text-danger">*</span>
								<input class="form-control" id="fecha de última lectura" name="fecha de última lectura" type="date" v-model="contador_temp.fecha_ultima_lectura" v-validate="'required'">
								<div class="invalid-feedback ">{{errors.first('contador.fecha de última lectura')}}</div>
							</div>
							<div class="form-group row">
								<div class="col-md-6">
									<label for="último mes">Último mes</label> <span class="text-danger">*</span>
									<select class="form-control" id="último mes" name="último mes" v-model="contador_temp.ultimo_mes" v-validate="'required'">
										<option value=""></option>
										<option value="1">enero</option>
										<option value="2">febrero</option>
										<option value="3">marzo</option>
										<option value="4">abril</option>
										<option value="5">mayo</option>
										<option value="6">junio</option>
										<option value="7">julio</option>
										<option value="8">agosto</option>
										<option value="9">septiembre</option>
										<option value="10">octubre</option>
										<option value="11">noviembre</option>
										<option value="12">diciembre</option>
									</select>
									<div class="invalid-feedback ">{{errors.first('contador.último mes')}}</div>
								</div>
								<div class="col-md-6">
									<label for="último año">Último año</label> <span class="text-danger">*</span>
									<input class="form-control" id="último año" name="último año" type="text" v-model="contador_temp.ultimo_anio" v-validate="'required|digits:4|min_value:2020'">
									<div class="invalid-feedback ">{{errors.first('contador.último año')}}</div>
								</div>
							</div>
							<div class="form-group">
								<label for="dirección de la casa en donde se encuentra el contador">Dirección de la casa en donde se encuentra el contador</label> <span class="text-danger">*</span>
								<input class="form-control" id="dirección de la casa en donde se encuentra el contador" name="dirección de la casa en donde se encuentra el contador" type="text" v-model="contador_temp.ubicacion" v-validate="'required'">
								<div class="invalid-feedback ">{{errors.first('contador.dirección de la casa en donde se encuentra el contador')}}</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<i v-bind:class="contador_temp.icon"></i>
								{{contador_temp.text}}
							</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">
								<i class="fas fa-times fa-lg mr-2"></i>
								Cerrar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				cliente: {},
				contadores: [],
				contador_temp: {},
				errors_server: []
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
			}
		},
		methods: {
			createContador() {
				this.errors_server = [];
				this.contador_temp = {}
				this.contador_temp.type = 'post'
				this.contador_temp.title = 'Crear contador'
				this.contador_temp.icon = 'fas fa-save fa-lg mr-2'
				this.contador_temp.text = 'Guardar contador'
				$('#contadorModal').modal('show')
			},
			editContador(index) {
				this.errors_server = [];
				this.contador_temp = {}
				this.contador_temp = this.contadores[index]
				this.contador_temp.type = 'put'
				this.contador_temp.title = 'Modificar contador'
				this.contador_temp.icon = 'fas fa-edit fa-lg mr-2'
				this.contador_temp.text = 'Actualizar contador'
				this.contador_temp.index = index
				$('#contadorModal').modal('show')
			},
			validarFormularioContador() {
				this.$validator.validateAll('contador')
				.then(esValido => {
					if (esValido) {
						this.contador_temp.type == 'post' ? this.storeContador() : this.updateContador()
					}
				})
			},
			storeContador() {
				axios.post(`/api/clientes/${this.cliente.id}/contadores`, this.contador_temp)
				.then(response => {
					Swal.fire({
						title: 'Contador registrado',
						html: `Se registró un contador para el cliente ${this.cliente.nombres} ${this.cliente.apellidos}`,
						icon: 'success'
					})
					.then(result => {
						this.contadores.push(response.data)
						$('#contadorModal').modal('toggle')
					})
				})
				.catch(error => {
					if (typeof error.response.data === 'object') {
						this.errors_server = _.flatten(_.toArray(error.response.data.errors))
					} else {
						this.errors_server = [error.response.data]
					}
				})
			},
			updateContador() {
				axios.put(`/api/clientes/${this.cliente.id}/contadores/${this.contador_temp.id}`, this.contador_temp)
				.then(response => {
					Swal.fire({
						title: 'Contador actualizado',
						html: `Se actualizó el contador ${this.contador_temp.id} del cliente ${this.cliente.nombres} ${this.cliente.apellidos}`,
						icon: 'success'
					})
					.then(result => {
						this.contadores[this.contador_temp.indexs] = response.data
						$('#contadorModal').modal('toggle')
					})
				})
				.catch(error => {
					if (typeof error.response.data === 'object') {
						this.errors_server = _.flatten(_.toArray(error.response.data.errors))
					} else {
						this.errors_server = [error.response.data]
					}
				})
			}
		}
	}
</script>