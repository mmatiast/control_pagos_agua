<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Formas de pago</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">Formas de pago</li>
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
						Registrar forma de pago
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
								<th>Forma de pago</th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(forma_pago, index) of buscarFormaDePago">
								<td>{{forma_pago.id}}</td>
								<td>{{forma_pago.forma_pago}}</td>
								<td>
									<span class="badge badge-success" v-show="forma_pago.deleted_at == null">
										Activo
									</span>
									<span class="badge badge-danger" v-show="forma_pago.deleted_at != null">
										Eliminado
									</span>
								</td>
								<td>
									<router-link class="btn btn-primary btn-sm" title="Mostrar forma de pago" :to="{name: 'formapagos.show', params: {id: forma_pago.id}}">
										<i class="fas fa-eye"></i>
									</router-link>
									<router-link class="btn btn-primary btn-sm" title="Modificar forma de pago" :to="{name: 'formapagos.edit', params: {id: forma_pago.id}}">
										<i class="fas fa-edit"></i>
									</router-link>
									<button class="delete btn btn-danger btn-sm" title="Eliminar forma de pago" v-bind:data-id="forma_pago.id" v-bind:data-index="index" v-show="forma_pago.deleted_at == null">
										<i class="fas fa-trash"></i>
									</button>
									<button class="delete btn btn-secondary btn-sm" title="Restaurar forma de pago" v-bind:data-id="forma_pago.id" v-bind:data-index="index" v-show="forma_pago.deleted_at != null">
										<i class="fas fa-trash-restore"></i>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<nav aria-label="...">
					<ul class="pagination justify-content-center">
						<li class="page-item" v-bind:class="{'disabled': formas.current_page == 1}">
							<a class="page-link" href="#" tabindex="-1" aria-disabled="true" v-on:click.prevent="cambiarPagina(formas.current_page - 1)">
								Anterior
							</a>
						</li>
						<li class="page-item" v-for="page in pagesNumber" v-bind:class="{'active': page == isActived}">
							<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(page)">{{page}}</a>
						</li>
						<li class="page-item" v-bind:class="{'disabled': formas.current_page == formas.last_page}">
							<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(formas.current_page + 1)">
								Siguiente
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="formaPagoModal" tabindex="-1" aria-labelledby="formaPagoModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="formaPagoModalLabel">
							<i class="fas fa-plus-circle fa-lg mr-2"></i>
							Nueva forma de pago
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
								<label for="forma de pago">Forma de pago</label> <span class="text-danger">*</span>
								<input class="form-control" id="forma de pago" name="forma de pago" type="text" v-model="forma_pago_temp.forma_pago" v-validate="'required'">
								<div class="invalid-feedback ">{{errors.first('forma de pago')}}</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-save fa-lg mr-2"></i>
								Guardar forma de pago
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
				formas: {
					data: []
				},
				busqueda: '',
				forma_pago_temp: {},
				index: 0,
				offset: 3,
				errorsServer: [],
			}
		},
		mounted() {
			this.obtenerFormas(1)
			$('#table tbody').on('click', 'button.delete', function(event) {
				this.index = event.currentTarget.dataset.index
				this.forma_pago_temp = this.formas.data[this.index]

				if(this.forma_pago_temp.deleted_at == null) {
					Swal.fire({
						title: 'Eliminar forma de pago',
						html: `¿Confirma la eliminación del forma:
						<span class="font-weight-bold">${this.formas.data[this.index].forma_pago}</span>?
						`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: '<i class="fas fa-thumbs-up fa-lg mr-2"></i>Si',
						cancelButtonText: '<i class="fas fa-times fa-lg mr-2"></i>No'
					}).then((result) => {
						if (result.value) {
							axios.delete('/api/formas-de-pago/'+this.forma_pago_temp.id)
							.then(response => {
								Swal.fire({
									title: 'Forma de pago eliminada',
									html: response.data,
									icon: 'warning'
								})
								.then(result => {
									if(result.value) {
										this.formas.data[this.index].deleted_at = Date.now()
									}
								})
							})
						}
					})
				} else {
					Swal.fire({
						title: 'Restaurar forma de pago',
						html: `¿Confirma la restauración de la forma de pago:
						<span class="font-weight-bold">${this.formas.data[this.index].forma_pago}</span>?
						`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: '<i class="fas fa-thumbs-up fa-lg mr-2"></i>Si',
						cancelButtonText: '<i class="fas fa-times fa-lg mr-2"></i>No'
					}).then((result) => {
						if (result.value) {
							axios.put(`/api/formas-de-pago/${this.forma_pago_temp.id}/restore`)
							.then(response => {
								Swal.fire({
									title: 'Forma de pago restaurada',
									html: response.data,
									icon: 'success'
								})
								.then(result => {
									this.formas.data[this.index].deleted_at = null
								})
							})
						}
					})
				}
			}.bind(this))
		},
		computed: {
			isActived: function() {
				return this.formas.current_page
			},
			pagesNumber: function() {
				if(!this.formas.to) {
					return []
				}
				let from = this.formas.current_page - this.offset
				if( from < 1) {
					from = 1
				}

				let to = from + (this.offset * 2)
				if(to >= this.formas.last_page) {
					to = this.formas.last_page
				}

				let pagesArray = []

				while(from <= to) {
					pagesArray.push(from)
					from++
				}

				return pagesArray
			},
			buscarFormaDePago() {
				return this.formas.data.filter((item) => item.forma_pago.toLowerCase().includes(this.busqueda.toLowerCase()))
			}
		},
		methods: {
			obtenerFormas(pagina) {
				axios.get('/api/formas-de-pago?page='+pagina)
				.then(response => {
					this.formas = response.data
				})
			},
			create() {
				this.forma_pago_temp = {}
				$('#formaPagoModal').modal('show')
			},
			store() {
				this.$validator.validate()
				.then(isValid => {
					if(isValid) {
						axios.post('/api/formas-de-pago', this.forma_pago_temp)
						.then(response => {
							Swal.fire({
								title: 'Registro exitoso',
								html: `La forma de pago <span class="font-weight-bold">${this.forma_pago_temp.forma_pago}</span> fue registrada exitosamente`,
								icon: 'success'
							})
							.then(result => {
								this.formas.data.push(response.data)
								$('#formaPagoModal').modal('toggle')
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
				if(this.formas.current_page != pagina) {
					this.formas.current_page = pagina
					this.obtenerFormas(pagina)
				}
			}
		}
	}
</script>