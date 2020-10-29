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
								<router-link to="/bancos">
									Formas de pago
								</router-link>
							</li>
							<li class="breadcrumb-item active">
								{{forma_pago.id}}
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="bs-callout bs-callout-success" v-show="show_alert">
				<h4>Forma de pago actualizada</h4>
				<p>
					La forma de pago con id <b>{{forma_pago.id}}</b> fue actualizada correctamente
				</p>
			</div>
			<div class="card">
				<div class="card-body">
					<h3 class="text-primary">
						Forma de pago {{forma_pago.id}}
					</h3>
					<form autocomplete="off">
						<div class="form-group">
							<label for="forma_pago">Forma de pago</label>
							<input class="form-control" type="text" v-model="forma_pago.forma_pago">
						</div>
					</form>
					<label for="">Creado</label>: {{forma_pago.created_at}} <br>
					<label for="">Actualizado</label>: {{forma_pago.updated_at}} <br>
					<label for="">Eliminado</label>: {{forma_pago.deleted_at ? forma_pago.deleted_at : 'La forma de pago se encuentra activa'}}
					<div class="form-group">
						<button class="btn btn-primary" v-on:click="update">
							<i class="fa fa-save fa-lg mr-2"></i>
							Actualizar
						</button>
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
				forma_pago: {},
				show_alert: false
			}
		},
		mounted() {
			axios.get('/api/formas-de-pago/'+this.$route.params.id)
			.then(response => {
				this.forma_pago = response.data
			})
		},
		methods: {
			update() {
				axios.put('/api/formas-de-pago/'+this.$route.params.id, this.forma_pago)
				.then(response => {
					this.forma_pago = response.data
					this.show_alert = true
				})
			}
		}
	}
</script>