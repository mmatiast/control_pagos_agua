<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Detalle de rol</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item">
								<router-link to="/roles">
									Roles
								</router-link>
							</li>
							<li class="breadcrumb-item active">
								{{role.id}}
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="bs-callout bs-callout-success" v-show="show_alert">
				<h4>Rol actualizado</h4>
				<p>
					El rol con id <b>{{role.id}}</b> fue actualizado correctamente
				</p>
			</div>
			<div class="card">
				<div class="card-body">
					<h3 class="text-primary">
						Rol {{role.id}}
					</h3>
					<form autocomplete="off">
						<div class="form-group">
							<label for="role">Nombre</label>
							<input class="form-control" type="text" v-model="role.role">
						</div>
					</form>
					<label for="">Creado</label>: {{role.created_at}} <br>
					<label for="">Actualizado</label>: {{role.updated_at}} <br>
					<label for="">Eliminado</label>: {{role.deleted_at ? role.deleted_at : 'El rol se encuentra activo'}}
					<div class="form-group">
						<button class="btn btn-primary" v-on:click="update">
							<i class="fa fa-save fa-lg mr-2"></i>
							Actualizar
						</button>
						<router-link  class="btn btn-secondary" :to="{name: 'roles.index'}">
							<i class="fa fa-arrow-left mr-2"></i>
							Regresar
						</router-link>
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
				role: {},
				show_alert: false
			}
		},
		mounted() {
			axios.get('/api/roles/'+this.$route.params.id)
			.then(response => {
				this.role = response.data
			})
		},
		methods: {
			update() {
				axios.put('/api/roles/'+this.$route.params.id, this.role)
				.then(response => {
					this.role = response.data
					this.show_alert = true
				})
			}
		}
	}
</script>