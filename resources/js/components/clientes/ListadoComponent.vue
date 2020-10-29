<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Listado general de clientes</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">							
							<li class="breadcrumb-item active">
								Listado general de clientes
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary mb-3" v-on:click="obtenerClientes(clientes.current_page)">
							<i class="fas fa-sync fa-lg mr-2"></i>
							Actualizar listado
						</button>
						<div class="bs-callout bs-callout-success bg-white" v-show="total_clientes == 0">
							<h4>Sin clientes clientes</h4>
							En este momento no hay ningún consumo en estado <b>pendiente de pago</b>
						</div>
						<section v-show="total_clientes > 0">
							<div class="form-group">
								<input class="form-control" id="texto a buscar" placeholder="Filtrar por nombre o cui del cliente" type="text" v-model="busqueda">
							</div>
							<div class="table-responsive">
								<table class="table table-hover border">
									<thead class="thead-light">
										<tr>
											<th scope="col">ID</th>
											<th scope="col">Cui</th>
											<th scope="col">Cliente</th>
											<th scope="col">Sexo</th>
											<th scope="col">Estado civil</th>
											<th scope="col">Celular</th>
											<th scope="col">Correo</th>
											<th scope="col"></th>
										</tr>
									</thead>
									<tbody class="bg-white">
										<tr v-for="cliente of filtrarCliente">
											<th scope="row">{{cliente.id}}</th>
											<td>{{cliente.cui}}</td>
											<td>{{cliente.nombres}} {{cliente.apellidos}}</td>
											<td>{{cliente.sexo}}</td>
											<td>{{cliente.estado_civil}}</td>
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
							</div>
							<nav aria-label="...">
								<ul class="pagination justify-content-center">
									<li class="page-item" v-bind:class="{'disabled': clientes.current_page == 1}">
										<a class="page-link" href="#" tabindex="-1" aria-disabled="true" v-on:click.prevent="cambiarPagina(clientes.current_page - 1)">
											Anterior
										</a>
									</li>
									<li class="page-item" v-for="page in pagesNumber" v-bind:class="{'active': page == isActived}">
										<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(page)">{{page}}</a>
									</li>
									<li class="page-item" v-bind:class="{'disabled': clientes.current_page == clientes.last_page}">
										<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(clientes.current_page + 1)">
											Siguiente
										</a>
									</li>
								</ul>
							</nav>
						</section>
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
				busqueda: '',
				clientes: {
					data: []
				},
				offset: 3
			}
		},
		computed: {
			isActived: function() {
				return this.clientes.current_page
			},
			pagesNumber: function() {
				if(!this.clientes.to) {
					return []
				}
				let from = this.clientes.current_page - this.offset
				if( from < 1) {
					from = 1
				}

				let to = from + (this.offset * 2)
				if(to >= this.clientes.last_page) {
					to = this.clientes.last_page
				}

				let pagesArray = []

				while(from <= to) {
					pagesArray.push(from)
					from++
				}

				return pagesArray
			},
			total_clientes() {
				return this.clientes.data.length
			},
			filtrarCliente() {
				return this.clientes.data.filter((cliente) => (cliente.cui + ' ' + cliente.nombres +' '+ cliente.apellidos).toLowerCase().includes(this.busqueda.toLowerCase()))
			}
		},
		mounted() {
			this.obtenerClientes(1)
		},
		methods: {
			obtenerClientes(pagina) {
				axios.get('/api/clientes?page='+pagina)
				.then(response => {
					this.clientes = response.data
				})
			},
			cambiarPagina(pagina) {
				if(this.clientes.current_page != pagina) {
					this.clientes.current_page = pagina
					this.obtenerClientes(pagina)
				}
			}
		}
	}
</script>