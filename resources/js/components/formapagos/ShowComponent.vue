<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Detalle de la forma de pago</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item">
								<router-link to="/formaspago">
									Formas de pago
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
						Forma de pago {{forma_pago.id}}
					</h3>
					<label for="">Nombre</label>: {{forma_pago.forma_pago}} <br>
					<label for="">Creado</label>: {{forma_pago.created_at}} <br>
					<label for="">Actualizado</label>: {{forma_pago.updated_at}} <br>
					<label for="">Eliminado</label>: {{forma_pago.deleted_at ? forma_pago.deleted_at : 'La forma de pago se encuentra activa'}}
					<div class="form-group">
						<router-link  class="btn btn-primary" :to="{name: 'formapagos.edit', params: { id: $route.params.id}}">
							<i class="fa fa-edit mr-2"></i>
							Modificar
						</router-link>
						<router-link  class="btn btn-secondary" :to="{name: 'formapagos.index'}">
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
				forma_pago: {}
			}
		},
		mounted() {
			axios.get('/api/formas-de-pago/'+this.$route.params.id)
			.then(response => {
				this.forma_pago = response.data
			})
		}
	}
</script>