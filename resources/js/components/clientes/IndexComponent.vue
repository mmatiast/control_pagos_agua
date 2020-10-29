<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Clientes</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">
								Búsqueda de clientes
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="card">
				<div class="card-body">
					<h5>Parámetro de búsqueda</h5>
					<form autocomplete="off" v-on:submit.prevent="search">
						<div class="form-group row">
							<div class="col-sm-3">
								<select class="form-control" v-model="form.tipo_busqueda">
									<option value="">Seleccione una opción</option>
									<option value="id">Código de cliente</option>
									<option value="cui">Códico único de identificación (dpi)</option>
									<option value="nombres">Nombres</option>
									<option value="apellidos">Apellidos</option>
								</select>
							</div>
							<div class="col-sm-9">
								<div class="input-group mb-3">
									<input class="form-control" id="texto a buscar" name="texto a buscar" placeholder="Introdúzca el texto a buscar" type="text" v-model="form.valor" v-validate="'required'">
									<div class="invalid-feedback ">{{errors.first('texto a buscar')}}</div>
									<div class="input-group-append">
										<button class="btn btn-primary">
											<i class="fas fa-search fa-lg mr-2"></i>
											Buscar
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<table class="table table-hover border" v-show="conteo_clientes">
						<thead class="thead-light">
							<tr>
								<th scope="col">Código</th>
								<th scope="col">Cui</th>
								<th scope="col">Nombre</th>
								<th scope="col">Celular</th>
								<th scope="col">Correo</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="cliente of clientes.data">
								<th scope="row">C-{{String(cliente.id).padStart(6, '000000')}}</th>
								<td>{{cliente.cui}}</td>
								<td>{{cliente.nombres}} {{cliente.apellidos}}</td>
								<td>{{cliente.celular}}</td>
								<td>{{cliente.correo}}</td>
								<td>
									<div class="btn-group dropleft">
										<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Opciones
										</button>
										<div class="dropdown-menu">
											<router-link class="dropdown-item" :to="{name: 'clientes.show', params: {id: cliente.id}}">
												<i class="fas fa-edit fw"></i>
												Modificar información
											</router-link>
											<router-link class="dropdown-item" :to="{name: 'clientes.contadores', params: {id: cliente.id}}">
												<i class="fas fa-stopwatch-20 fw"></i>
												Contadores
											</router-link>
											<router-link class="dropdown-item" :to="{name: 'clientes.consumos', params: {id: cliente.id}}">
												<i class="fas fa-tachometer-alt fw"></i>
												Consumos
											</router-link>
											<router-link class="dropdown-item" :to="{name: 'clientes.pagos', params: {id: cliente.id}}">
												<i class="fas fa-money-bill-alt fw"></i>
												Pagos
											</router-link>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="alert alert-info" role="alert" v-show="not_found">
						<h4 class="alert-heading">Búsqueda sin resultado</h4>
						<p>
							No hay registros en la base de datos con el parámetro
							"<span class="font-weight-bold">{{form.tipo_busqueda}}</span>" y el valor
							"<span class="font-weight-bold">{{form.valor}}</span>"
						</p>
						<hr>
						<p class="mb-0">Por favor intente nuevamente.</p>
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
				form: {
					tipo_busqueda: 'id'
				},
				clientes: {
					data: []
				},
				not_found: false
			}
		},
		computed: {
			conteo_clientes(){
				return this.clientes.data.length
			}
		},
		methods: {
			search() {
				this.not_found = false
				this.$validator.validate()
				.then(isValid => {
					if(isValid) {
						this.form.operador = this.form.tipo_busqueda == 'nombres' || this.form.tipo_busqueda == 'apellidos' ? 'LIKE' : '='
						axios.get(`/api/clientes/search?parametro=${this.form.tipo_busqueda}&operador=${this.form.operador}&valor=${this.form.valor}`)
						.then(response => {
							if(response.data.data.length == 0) {this.not_found=true}
								this.clientes = response.data
						})
					}
				})
			}
		}
	}
</script>