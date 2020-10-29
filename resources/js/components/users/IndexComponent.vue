<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Usuarios</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item">
								<router-link to="home">
									Home
								</router-link>
							</li>
							<li class="breadcrumb-item active">Usuarios</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<section class="content">
			<div class="card card-solid">
				<div class="card-header">
					<button type="button" class="btn btn-primary" v-on:click="create">
						<i class="far fa-plus-square fa-lg mr-2"></i>
						Registrar usuario
					</button>
					<div class="card-tools">
						<input type="text" name="table_search" class="form-control float-right" placeholder="Búsqueda por cui" v-model="busqueda">
					</div>
				</div>
				<div class="card-body pb-0">
					<div class="row d-flex align-items-stretch">
						<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch" v-for="(user, index) of buscarUsuario">
							<div class="card bg-light">
								<div class="card-header text-center text-muted border-bottom-0">
									{{user.id}}
								</div>
								<div class="card-body pt-0">
									<div class="row">
										<div class="col-7">
											<h2 class="lead">
												<b>{{user.name}}</b>
											</h2>
											{{roles[user.role_id -1 ].role}}
											<p class="text-muted text-sm">
												<b>Correo:</b>
												{{user.email}}
											</p>
											<ul class="ml-4 mb-0 fa-ul text-muted">
												<li class="small">
													<span class="fa-li">
														<i class="fas fa-lg fa-building"></i>
													</span>
													Cui: {{user.cui}}
												</li>
											</ul>
										</div>
										<div class="col-5 text-center">
											<img src="/images/male.png" alt="male" class="img-circle img-fluid" v-show="user.sexo == 'M'">
											<img src="/images/female.png" alt="female" class="img-circle img-fluid" v-show="user.sexo == 'F'">
										</div>
									</div>
								</div>
								<div class="card-footer">
									<div class="text-right">
										<div v-show="user.deleted_at == null">
											<router-link class="btn btn-sm btn-primary" :to="{name: 'users.edit', params: {id: user.id}}">
												<i class="fas fa-user fa-lg mr-2"></i>
												Modificar
											</router-link>
											<button class="btn btn-danger btn-sm" v-on:click="eliminarUsuario(index)">
												<i class="fas fa-trash fa-lg mr-2"></i>
												Eliminar
											</button>
										</div>
										<button class="btn btn-secondary btn-sm" v-show="user.deleted_at != null" v-on:click="restaurarUsuario(index)">
											<i class="fas fa-trash-restore fa-lg mr-2"></i>
											Restaurar
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<nav aria-label="...">
						<ul class="pagination justify-content-center">
							<li class="page-item" v-bind:class="{'disabled': users.current_page == 1}">
								<a class="page-link" href="#" tabindex="-1" aria-disabled="true" v-on:click.prevent="cambiarPagina(users.current_page - 1)">
									Anterior
								</a>
							</li>
							<li class="page-item" v-for="page in pagesNumber" v-bind:class="{'active': page == isActived}">
								<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(page)">{{page}}</a>
							</li>
							<li class="page-item" v-bind:class="{'disabled': users.current_page == users.last_page}">
								<a class="page-link" href="#" v-on:click.prevent="cambiarPagina(users.current_page + 1)">
									Siguiente
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</section>
		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="userModalLabel">
							<i class="fas fa-user-plus fa-lg mr-2"></i>
							Nuevo usuario
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form autocomplete="off" class="form-horizontal" v-on:submit.prevent="store">
						<div class="modal-body">
							<div class="alert alert-danger mt-3" role="alert" v-show="errorsServer.length > 0">
								<ul>
									<li v-for="error in errorsServer">{{error}}</li>
								</ul>
							</div>
							<div class="form-group row">
								<label for="name" class="col-sm-3 col-form-label">
									Nombre <span class="text-danger">*</span>
								</label>
								<div class="col-sm-9">
									<input class="form-control" id="name" name="name" type="text" placeholder="" v-model="user.name" v-validate="'required'">
									<div class="invalid-feedback ">{{errors.first('name')}}</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="email" class="col-sm-3 col-form-label">
									Correo <span class="text-danger">*</span>
								</label>
								<div class="col-sm-9">
									<input class="form-control" id="email" name="email" type="email" placeholder="" v-model="user.email" v-validate="'required|email'">
									<div class="invalid-feedback ">{{errors.first('email')}}</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="cui" class="col-sm-3 col-form-label">
									Cui <span class="text-danger">*</span>
								</label>
								<div class="col-sm-9">
									<input class="form-control" id="cui" name="cui" type="tel" placeholder="" v-model="user.cui" v-validate="'required|min:13|max:13|validar_dpi'">
									<div class="invalid-feedback ">{{errors.first('cui')}}</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="sexo" class="col-sm-3 col-form-label">
									Sexo <span class="text-danger">*</span>
								</label>
								<div class="col-sm-9">
									<select class="form-control" id="sexo" name="sexo" v-model="user.sexo" v-validate="'required'">
										<option value=""></option>
										<option value="M">Masculino</option>
										<option value="F">Femenino</option>
									</select>
									<div class="invalid-feedback ">{{errors.first('sexo')}}</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="rol" class="col-sm-3 col-form-label">
									Rol <span class="text-danger">*</span>
								</label>
								<div class="col-sm-9">
									<select class="form-control" id="rol" name="rol" v-model="user.role_id" v-validate="'required'">
										<option value=""></option>
										<option v-bind:value="role.id" v-for="role of roles">{{role.role}}</option>
									</select>
									<div class="invalid-feedback ">{{errors.first('rol')}}</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="contraseña" class="col-sm-3 col-form-label">
									Contraseña <span class="text-danger">*</span>
								</label>
								<div class="col-sm-9">
									<input class="form-control" id="contraseña" name="contraseña" type="password" ref="password"  placeholder="" v-model="user.password" v-validate="'required|min:8'">
									<div class="invalid-feedback ">{{errors.first('contraseña')}}</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="confirmación de contraseña" class="col-sm-3 col-form-label">
									Confirmar contraseña <span class="text-danger">*</span>
								</label>
								<div class="col-sm-9">
									<input class="form-control" id="confirmación de contraseña" name="confirmación de contraseña" type="password" placeholder="" v-model="user.password_confirmation" v-validate="'required|min:8|confirmed:password'">
									<div class="invalid-feedback ">{{errors.first('confirmación de contraseña')}}</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-save fa-lg mr-2"></i>
								Guardar usuario
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
				users: {
					data: []
				},
				busqueda: '',
				user: {},
				roles: [],
				offset: 3,
				errorsServer: [],
			}
		},
		mounted() {
			this.obtenerRoles(this.obtenerUsuarios)
		},
		computed: {
			isActived: function() {
				return this.users.current_page
			},
			pagesNumber: function() {
				if(!this.users.to) {
					return []
				}
				let from = this.users.current_page - this.offset
				if( from < 1) {
					from = 1
				}

				let to = from + (this.offset * 2)
				if(to >= this.users.last_page) {
					to = this.users.last_page
				}

				let pagesArray = []

				while(from <= to) {
					pagesArray.push(from)
					from++
				}

				return pagesArray
			},
			buscarUsuario() {
				return this.users.data.filter((item) => item.name.toLowerCase().includes(this.busqueda.toLowerCase()))
			}
		},
		methods: {
			create() {
				this.user = {}
				$('#userModal').modal('show')
			},
			store() {
				this.$validator.validate()
				.then(isValid => {
					if(isValid) {
						axios.post('/api/users', this.user)
						.then(response => {
							Swal.fire({
								title: 'Registro exitoso',
								html: `El usuario <span class="font-weight-bold">${this.user.name}</span> fue registrado exitosamente`,
								icon: 'success'
							})
							this.users.data.push(response.data)
							$('#userModal').modal('toggle')
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
			obtenerRoles(callback) {
				axios.get('/api/roles?paginate=no')
				.then(response => {
					this.roles = response.data
					callback(1)
				})
			},
			obtenerUsuarios(pagina) {
				axios.get('/api/users?page='+pagina)
				.then(response => {
					this.users = response.data
				})
			},
			eliminarUsuario(index) {
				this.user = this.users.data[index]
				Swal.fire({
					title: 'Eliminar usuario',
					html: `¿Confirma la eliminación del usuario:
					<span class="font-weight-bold">${this.user.name}</span>?
					`,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: '<i class="fas fa-thumbs-up fa-lg mr-2"></i>Si',
					cancelButtonText: '<i class="fas fa-times fa-lg mr-2"></i>No'
				}).then((result) => {
					if (result.value) {
						axios.delete('/api/users/'+this.user.id)
						.then(response => {
							Swal.fire({
								title: 'Usuario eliminado',
								html: response.data,
								icon: 'warning'
							})
							.then(result => {
								if(result.value) {
									this.users.data[index].deleted_at = Date.now()
								}
							})
						})
					}
				})
			},
			restaurarUsuario(index) {
				this.user = this.users.data[index]
				Swal.fire({
					title: 'Restaurar usuario',
					html: `¿Confirma la restauración del usuario:
					<span class="font-weight-bold">${this.user.name}</span>?
					`,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: '<i class="fas fa-thumbs-up fa-lg mr-2"></i>Si',
					cancelButtonText: '<i class="fas fa-times fa-lg mr-2"></i>No'
				}).then((result) => {
					if (result.value) {
						axios.put(`/api/users/${this.user.id}/restore`)
						.then(response => {
							Swal.fire({
								title: 'Usuario restaurado',
								html: response.data,
								icon: 'success'
							})
							.then(result => {
								this.users.data[index].deleted_at = null
							})
						})
					}
				})
			},
			cambiarPagina(pagina) {
				if(this.users.current_page != pagina) {
					this.users.current_page = pagina
					this.obtenerUsuarios(pagina)
				}
			}
		}
	}
</script>