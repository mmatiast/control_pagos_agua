<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Roles</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">Roles</li>
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
						Registrar rol
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
								<th>Role</th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(role, index) of buscarRol">
								<td>{{role.id}}</td>
								<td>{{role.role}}</td>
								<td>
									<span class="badge badge-success" v-show="role.deleted_at == null">
										Activo
									</span>
									<span class="badge badge-danger" v-show="role.deleted_at != null">
										Eliminado
									</span>
								</td>
								<td>
									<router-link class="btn btn-primary btn-sm" title="Mostrar rol" :to="{name: 'roles.show', params: {id: role.id}}">
										<i class="fas fa-eye"></i>
									</router-link>
									<router-link class="btn btn-primary btn-sm" title="Modificar rol" :to="{name: 'roles.edit', params: {id: role.id}}">
										<i class="fas fa-edit"></i>
									</router-link>
									<button class="delete btn btn-danger btn-sm" title="Eliminar rol" v-bind:data-id="role.id" v-bind:data-index="index" v-show="role.deleted_at == null">
										<i class="fas fa-trash"></i>
									</button>
									<button class="delete btn btn-secondary btn-sm" title="Restaurar rol" v-bind:data-id="role.id" v-bind:data-index="index" v-show="role.deleted_at != null">
										<i class="fas fa-trash-restore"></i>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<nav aria-label="...">
					<ul class="pagination justify-content-center">
						<li class="page-item" v-bind:class="{'disabled': roles.current_page == 1}">
							<a class="page-link" href="#" tabindex="-1" aria-disabled="true" v-on:click.prevent="cambiarPagina(roles.current_page - 1)">
								Anterior
							</a>
						</li>
						<li class="page-item" v-for="page in pagesNumber" v-bind:class="{'active': page == isActived}">
							<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(page)">{{page}}</a>
						</li>
						<li class="page-item" v-bind:class="{'disabled': roles.current_page == roles.last_page}">
							<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(roles.current_page + 1)">
								Siguiente
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="roleModalLabel">
							<i class="fas fa-plus-circle fa-lg mr-2"></i>
							Nuevo rol
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
								<label for="rol">Nombre del rol</label> <span class="text-danger">*</span>
								<input class="form-control" id="rol" name="rol" type="text" v-model="role_temp.role" v-validate="'required'">
								<div class="invalid-feedback ">{{errors.first('rol')}}</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-save fa-lg mr-2"></i>
								Guardar rol
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
				roles: {
					data: []
				},
				busqueda: '',
				role_temp: {},
				index: 0,
				offset: 3,
				errorsServer: []
			}
		},
		mounted() {
			this.obtenerRoles(1)

			$('#table tbody').on('click', 'button.delete', function(event) {
				this.index = event.currentTarget.dataset.index
				this.role_temp = this.roles.data[this.index]

				if(this.role_temp.deleted_at == null) {
					Swal.fire({
						title: 'Eliminar rol',
						html: `¿Confirma la eliminación del rol:
						<span class="font-weight-bold">${this.roles.data[event.currentTarget.dataset.index].role}</span>?
						`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: '<i class="fas fa-thumbs-up fa-lg mr-2"></i>Si',
						cancelButtonText: '<i class="fas fa-times fa-lg mr-2"></i>No'
					}).then((result) => {
						if (result.value) {
							axios.delete('/api/roles/'+this.role_temp.id)
							.then(response => {
								Swal.fire({
									title: 'Rol eliminado',
									html: response.data,
									icon: 'warning'
								})
								.then(result => {
									if(result.value) {
										this.roles.data[this.index].deleted_at = Date.now()
									}
								})
							})
						}
					})
				} else {
					Swal.fire({
						title: 'Restaurar rol',
						html: `¿Confirma la restauración del rol:
						<span class="font-weight-bold">${this.roles.data[event.currentTarget.dataset.index].role}</span>?
						`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: '<i class="fas fa-thumbs-up fa-lg mr-2"></i>Si',
						cancelButtonText: '<i class="fas fa-times fa-lg mr-2"></i>No'
					}).then((result) => {
						if (result.value) {
							axios.put(`/api/roles/${this.role_temp.id}/restore`)
							.then(response => {
								Swal.fire({
									title: 'Rol restaurado',
									html: response.data,
									icon: 'success'
								})
								.then(result => {
									this.roles.data[this.index].deleted_at = null
								})
							})
						}
					})
				}
			}.bind(this))
		},
		computed: {
			isActived: function() {
				return this.roles.current_page
			},
			pagesNumber: function() {
				if(!this.roles.to) {
					return []
				}
				let from = this.roles.current_page - this.offset
				if( from < 1) {
					from = 1
				}

				let to = from + (this.offset * 2)
				if(to >= this.roles.last_page) {
					to = this.roles.last_page
				}

				let pagesArray = []

				while(from <= to) {
					pagesArray.push(from)
					from++
				}

				return pagesArray
			},
			buscarRol() {
				return this.roles.data.filter((item) => item.role.toLowerCase().includes(this.busqueda.toLowerCase()))
			}
		},
		methods: {
			obtenerRoles(pagina) {
				axios.get('/api/roles?page='+pagina)
				.then(response => {
					this.roles = response.data
				})
			},
			create() {
				this.role_temp = {}
				$('#roleModal').modal('show')
			},
			store() {
				this.$validator.validate()
				.then(isValid => {
					if(isValid) {
						axios.post('/api/roles', this.role_temp)
						.then(response => {
							Swal.fire({
								title: 'Registro exitoso',
								html: `El rol <span class="font-weight-bold">${this.role_temp.role}</span> fue registrado exitosamente`,
								icon: 'success'
							})
							this.roles.data.push(response.data)
							$('#roleModal').modal('toggle')
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
				if(this.roles.current_page != pagina) {
					this.roles.current_page = pagina
					this.obtenerRoles(pagina)
				}
			}
		}
	}
</script>