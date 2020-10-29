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
								{{this.$route.params.id}}
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="card">
				<div class="card-body">
					<h3 class="text-primary">
						Rol {{role.id}}
					</h3>
					<label for="">Nombre</label>: {{role.role}} <br>
					<label for="">Creado</label>: {{role.created_at}} <br>
					<label for="">Actualizado</label>: {{role.updated_at}} <br>
					<label for="">Eliminado</label>: {{role.deleted_at ? role.deleted_at : 'El rol se encuentra activo'}}
					<div class="form-group">
						<router-link  class="btn btn-primary" :to="{name: 'roles.edit', params: { id: $route.params.id}}">
							<i class="fa fa-edit mr-2"></i>
							Modificar
						</router-link>
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
				role: {}
			}
		},
		mounted() {
			axios.get('/api/roles/'+this.$route.params.id)
			.then(response => {
				this.role = response.data
			})
		}
	}
</script>