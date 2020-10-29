<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Bancos</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">Bancos</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="card">
				<div class="card-header">
					<button type="button" class="btn btn-primary" v-on:click="create">
						<i class="far fa-plus-square fa-lg mr-2"></i>
						Registrar banco
					</button>
					<div class="card-tools">
						<input type="text" name="table_search" class="form-control float-right" placeholder="Búsqueda" v-model="busqueda">
					</div>
				</div>
				<div class="card-body table-responsive p-0">
					<table class="table table-hover text-nowrap" id="table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Banco</th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(banco, index) of buscarBanco">
								<td>{{banco.id}}</td>
								<td>{{banco.banco}}</td>
								<td>
									<span class="badge badge-success" v-show="banco.deleted_at == null">
										Activo
									</span>
									<span class="badge badge-danger" v-show="banco.deleted_at != null">
										Eliminado
									</span>
								</td>
								<td>
									<router-link class="btn btn-primary btn-sm" title="Mostrar banco" :to="{name: 'bancos.show', params: {id: banco.id}}">
										<i class="fas fa-eye"></i>
									</router-link>
									<router-link class="btn btn-primary btn-sm" title="Modificar banco" :to="{name: 'bancos.edit', params: {id: banco.id}}">
										<i class="fas fa-edit"></i>
									</router-link>
									<button class="delete btn btn-danger btn-sm" title="Eliminar banco" v-bind:data-id="banco.id" v-bind:data-index="index" v-show="banco.deleted_at == null">
										<i class="fas fa-trash"></i>
									</button>
									<button class="delete btn btn-secondary btn-sm" title="Restaurar banco" v-bind:data-id="banco.id" v-bind:data-index="index" v-show="banco.deleted_at != null">
										<i class="fas fa-trash-restore"></i>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<nav aria-label="...">
					<ul class="pagination justify-content-center">
						<li class="page-item" v-bind:class="{'disabled': bancos.current_page == 1}">
							<a class="page-link" href="#" tabindex="-1" aria-disabled="true" v-on:click.prevent="cambiarPagina(bancos.current_page - 1)">
								Anterior
							</a>
						</li>
						<li class="page-item" v-for="page in pagesNumber" v-bind:class="{'active': page == isActived}">
							<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(page)">{{page}}</a>
						</li>
						<li class="page-item" v-bind:class="{'disabled': bancos.current_page == bancos.last_page}">
							<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(bancos.current_page + 1)">
								Siguiente
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="bancoModal" tabindex="-1" aria-labelledby="bancoModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="bancoModalLabel">
							<i class="fas fa-plus-circle fa-lg mr-2"></i>
							Nuevo banco
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form autocomplete="off" v-on:submit.prevent="store">
						<div class="modal-body">
							<div class="alert alert-danger mt-3" role="alert" v-show="errorsServer.length > 0">
								<ul>
									<li v-for="error in errorsServer">{{error}}</li>
								</ul>
							</div>
							<div class="form-group">
								<label for="banco">Nombre del banco</label> <span class="text-danger">*</span>
								<input class="form-control" id="banco" name="banco" type="text" v-model="banco_temp.banco" v-validate="'required'">
								<div class="invalid-feedback ">{{errors.first('banco')}}</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-save fa-lg mr-2"></i>
								Guardar banco
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
				bancos: {
					data: []
				},
				busqueda: '',
				banco_temp: {},
				index: 0,
				errorsServer: [],
				offset: 3
			}
		},
		mounted() {
			this.obtenerBancos(1)

			$('#table tbody').on('click', 'button.delete', function(event) {
				this.index = event.currentTarget.dataset.index
				this.banco_temp = this.bancos.data[this.index]

				if(this.banco_temp.deleted_at == null) {
					Swal.fire({
						title: 'Eliminar banco',
						html: `¿Confirma la eliminación del banco:
						<span class="font-weight-bold">${this.bancos.data[event.currentTarget.dataset.index].banco}</span>?
						`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: '<i class="fas fa-thumbs-up fa-lg mr-2"></i>Si',
						cancelButtonText: '<i class="fas fa-times fa-lg mr-2"></i>No'
					}).then((result) => {
						if (result.value) {
							axios.delete('/api/bancos/'+this.banco_temp.id)
							.then(response => {
								Swal.fire({
									title: 'Banco eliminado',
									html: response.data,
									icon: 'warning'
								})
								.then(result => {
									if(result.value) {
										this.bancos.data[this.index].deleted_at = Date.now()
									}
								})
							})
						}
					})
				} else {
					Swal.fire({
						title: 'Restaurar banco',
						html: `¿Confirma la restauración del banco:
						<span class="font-weight-bold">${this.bancos.data[event.currentTarget.dataset.index].banco}</span>?
						`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: '<i class="fas fa-thumbs-up fa-lg mr-2"></i>Si',
						cancelButtonText: '<i class="fas fa-times fa-lg mr-2"></i>No'
					}).then((result) => {
						if (result.value) {
							axios.put(`/api/bancos/${this.banco_temp.id}/restore`)
							.then(response => {
								Swal.fire({
									title: 'Banco restaurado',
									html: response.data,
									icon: 'success'
								})
								.then(result => {
									this.bancos.data[this.index].deleted_at = null
								})
							})
						}
					})
				}
			}.bind(this))
		},
		computed: {
			isActived: function() {
				return this.bancos.current_page
			},
			pagesNumber: function() {
				if(!this.bancos.to) {
					return []
				}
				let from = this.bancos.current_page - this.offset
				if( from < 1) {
					from = 1
				}

				let to = from + (this.offset * 2)
				if(to >= this.bancos.last_page) {
					to = this.bancos.last_page
				}

				let pagesArray = []

				while(from <= to) {
					pagesArray.push(from)
					from++
				}

				return pagesArray
			},
			buscarBanco() {
				return this.bancos.data.filter((item) => item.banco.toLowerCase().includes(this.busqueda.toLowerCase()))
			}
		},
		methods: {
			obtenerBancos(pagina) {
				axios.get('/api/bancos?page='+pagina)
				.then(response => {
					this.bancos = response.data
				})
			},
			create() {
				this.banco_temp = {}
				$('#bancoModal').modal('show')
			},
			store() {
				this.$validator.validate()
				.then(isValid => {
					if(isValid) {
						axios.post('/api/bancos', this.banco_temp)
						.then(response => {
							Swal.fire({
								title: 'Registro exitoso',
								html: `El banco <span class="font-weight-bold">${this.banco_temp.banco}</span> fue registrado exitosamente`,
								icon: 'success'
							})
							.then(result => {
								this.bancos.data.push(response.data)
								$('#bancoModal').modal('toggle')
							})
						})
						.catch(error => {
							this.errorsServer = [];
							if (typeof error.response.data === 'object') {
								this.errorsServer = _.flatten(_.toArray(error.response.data.errors))
							} else {
								this.errorsServer = [error.response.data]
							}
						})
					}
				})
			},
			cambiarPagina(pagina) {
				if(this.bancos.current_page != pagina) {
					this.bancos.current_page = pagina
					this.obtenerBancos(pagina)
				}
			}
		}
	}
</script>