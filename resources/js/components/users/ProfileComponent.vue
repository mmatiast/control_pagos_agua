<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Perfil de usuario</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item">
								Usuarios
							</li>
							<li class="breadcrumb-item active">
								{{$store.state.user.id}}
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
									<img class="profile-user-img img-fluid img-circle" src="/images/male.png" alt="User profile picture" v-show="$store.state.user.sexo == 'M'">
									<img class="profile-user-img img-fluid img-circle" src="/images/female.png" alt="User profile picture" v-show="$store.state.user.sexo == 'F'">
								</div>
								<h3 class="profile-username text-center">
									{{$store.state.user.name}}
								</h3>
								<p class="text-muted text-center">
									{{$store.state.user.rol}}
								</p>
								<ul class="list-group list-group-unbordered mb-3">
									<li class="list-group-item">
										<b>Cui</b>
										<a class="float-right">
											{{$store.state.user.cui}}
										</a>
									</li>
									<li class="list-group-item">
										<b>Email</b>
										<a class="float-right">
											{{$store.state.user.email}}
										</a>
									</li>
								</ul>
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
										<form autocomplete="off" class="form-horizontal">
											<div class="form-group row">
												<label for="name" class="col-sm-2 col-form-label">
													Nombre
												</label>
												<div class="col-sm-10">
													<input class="form-control" disabled id="name" name="name" type="text" placeholder="" v-model="$store.state.user.name">
												</div>
											</div>
											<div class="form-group row">
												<label for="email" class="col-sm-2 col-form-label">
													Correo
												</label>
												<div class="col-sm-10">
													<input class="form-control" disabled id="email" name="email" type="email" placeholder="" v-model="$store.state.user.email">
												</div>
											</div>
											<div class="form-group row">
												<label for="cui" class="col-sm-2 col-form-label">
													Cui
												</label>
												<div class="col-sm-10">
													<input class="form-control" disabled id="cui" name="cui" type="tel" placeholder="" v-model="$store.state.user.cui">
												</div>
											</div>
											<div class="form-group row">
												<label for="sexo" class="col-sm-2 col-form-label">
													Sexo
												</label>
												<div class="col-sm-10">
													<select class="form-control" disabled id="sexo" name="sexo" v-model="$store.state.user.sexo">
														<option value=""></option>
														<option value="M">Masculino</option>
														<option value="F">Femenino</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="role_id" class="col-sm-2 col-form-label">
													Rol
												</label>
												<div class="col-sm-10">
													<select class="form-control" disabled id="role_id" name="role_id" v-model="$store.state.user.role_id">
														<option value=""></option>
														<option v-bind:value="role.id" v-for="role of roles">{{role.role}}</option>
													</select>
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
				roles: []
			}
		},
		mounted() {
			axios.get('/api/roles?paginate=no')
			.then(response => {
				this.roles = response.data
			})
		}
	}
</script>