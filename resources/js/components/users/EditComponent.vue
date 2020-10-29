<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Modificar usuario</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item">
								<router-link :to="{name: 'users.index'}">
									Usuarios
								</router-link>
							</li>
							<li class="breadcrumb-item active">
								{{user.id}}
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
									<img class="profile-user-img img-fluid img-circle" src="/images/male.png" alt="User profile picture" v-show="user.sexo == 'M'">
									<img class="profile-user-img img-fluid img-circle" src="/images/female.png" alt="User profile picture" v-show="user.sexo == 'F'">
								</div>
								<h3 class="profile-username text-center">
									{{user.name}}
								</h3>
								<p class="text-muted text-center">
									{{user.rol}}
								</p>
								<ul class="list-group list-group-unbordered mb-3">
									<li class="list-group-item">
										<b>Cui</b>
										<a class="float-right">
											{{user.cui}}
										</a>
									</li>
									<li class="list-group-item">
										<b>Email</b>
										<a class="float-right">
											{{user.email}}
										</a>
									</li>
								</ul>
								<router-link to="/users" class="btn btn-secondary btn-block">
									<i class="fas fa-arrow-left mr-2"></i>
									Regresar
								</router-link>
							</div>
						</div>
					</div>
					<div class="col-md-9">
						<div class="card">
							<div class="card-header p-2">
								<ul class="nav nav-pills">
									<li class="nav-item">
										<a class="nav-link active" href="#datos" data-toggle="tab">
											Datos generales
										</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane active" id="datos">
										<div class="bs-callout bs-callout-success" v-show="show_alert">
											<h4>Usuario actualizado</h4>
											<p>
												El usuario con id <b>{{user.id}}</b> fue actualizado correctamente
											</p>
										</div>
										<form autocomplete="off" class="form-horizontal" v-on:submit.prevent="update">
											<div class="form-group row">
												<label for="name" class="col-sm-2 col-form-label">
													Nombre
												</label>
												<div class="col-sm-10">
													<input class="form-control" id="name" name="name" type="text" placeholder="" v-model="user.name" v-validate="'required'">
													<div class="invalid-feedback ">{{errors.first('name')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label for="email" class="col-sm-2 col-form-label">
													Correo
												</label>
												<div class="col-sm-10">
													<input class="form-control" id="email" name="email" type="email" placeholder="" v-model="user.email" v-validate="'required|email'">
													<div class="invalid-feedback ">{{errors.first('email')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label for="cui" class="col-sm-2 col-form-label">
													Cui
												</label>
												<div class="col-sm-10">
													<input class="form-control" id="cui" name="cui" type="tel" placeholder="" v-model="user.cui" v-validate="'required|min:13|max:13|validar_dpi'">
													<div class="invalid-feedback ">{{errors.first('cui')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label for="sexo" class="col-sm-2 col-form-label">
													Sexo
												</label>
												<div class="col-sm-10">
													<select class="form-control" id="sexo" name="sexo" v-model="user.sexo" v-validate="'required'">
														<option value=""></option>
														<option value="M">Masculino</option>
														<option value="F">Femenino</option>
													</select>
													<div class="invalid-feedback ">{{errors.first('sexo')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<label for="role_id" class="col-sm-2 col-form-label">
													Rol
												</label>
												<div class="col-sm-10">
													<select class="form-control" id="role_id" name="role_id" v-model="user.role_id" v-validate="'required'">
														<option value=""></option>
														<option v-bind:value="role.id" v-for="role of roles">{{role.role}}</option>
													</select>
													<div class="invalid-feedback ">{{errors.first('role_id')}}</div>
												</div>
											</div>
											<div class="form-group row">
												<div class="offset-sm-2 col-sm-10">
													<button type="submit" class="btn btn-primary">
														<i class="fas fa-save fa-lg mr-2"></i>
														Actualizar usuario
													</button>
												</div>
											</div>
										</form>
									</div>
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
				user: {},
				roles: [],
				show_alert: false
			}
		},
		mounted() {
			axios.get('/api/roles?paginate=no')
			.then(response => {
				this.roles = response.data
				axios.get('/api/users/'+this.$route.params.id)
				.then(response => {
					this.user = response.data
					this.user.rol = this.roles[this.user.role_id-1].role
				})
				.catch(error => {
					if(error.response.status == 403) {
						Swal.fire({
							title: 'Permiso denegado',
							html: 'Usted no tiene permisos para visualizar a este usuario',
							icon: 'warning'
						})
					}
				})
			})
		},
		methods: {
			update() {
				this.$validator.validate()
				.then(isValid => {
					if (isValid) {
						axios.put('/api/users/'+this.$route.params.id, this.user)
						.then(response => {
							this.user = response.data
							this.show_alert = true
						})
					}
				})
			}
		}
	}
</script>