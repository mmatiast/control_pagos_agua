<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Detalle de banco</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item">
								<router-link to="/bancos">
									Roles
								</router-link>
							</li>
							<li class="breadcrumb-item active">
								{{banco.id}}
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="bs-callout bs-callout-success" v-show="show_alert">
				<h4>Banco actualizado</h4>
				<p>
					El banco con id <b>{{banco.id}}</b> fue actualizado correctamente
				</p>
			</div>
			<div class="card">
				<div class="card-body">
					<h3 class="text-primary">
						Banco {{banco.id}}
					</h3>
					<form autocomplete="off">
						<div class="form-group">
							<label for="banco">Nombre</label>
							<input class="form-control" type="text" v-model="banco.banco">
						</div>
					</form>
					<label for="">Creado</label>: {{banco.created_at}} <br>
					<label for="">Actualizado</label>: {{banco.updated_at}} <br>
					<label for="">Eliminado</label>: {{banco.deleted_at ? banco.deleted_at : 'El banco se encuentra activo'}}
					<div class="form-group">
						<button class="btn btn-primary" v-on:click="update">
							<i class="fa fa-save fa-lg mr-2"></i>
							Actualizar
						</button>
						<router-link  class="btn btn-secondary" :to="{name: 'bancos.index'}">
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
				banco: {},
				show_alert: false
			}
		},
		mounted() {
			axios.get('/api/bancos/'+this.$route.params.id)
			.then(response => {
				this.banco = response.data
			})
		},
		methods: {
			update() {
				axios.put('/api/bancos/'+this.$route.params.id, this.banco)
				.then(response => {
					this.banco = response.data
					this.show_alert = true
				})
			}
		}
	}
</script>