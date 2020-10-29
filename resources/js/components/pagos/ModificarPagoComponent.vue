<template>
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Modificar boleta de pago</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">
								Modificar pago
							</li>
						</ol>
					</div>
				</div>
			</div>
		</section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<form autocomplete="off" v-on:submit.prevent="onSubmit">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label float-right" for="fecha de boleta">Fecha de boleta</label>
										<div class="col-sm-8">
											<input class="form-control" id="fecha de boleta" name="fecha de boleta" type="date" v-model="pago.fecha_deposito" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('fecha de boleta')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label float-right" for="número de boleta">Número de boleta</label>
										<div class="col-sm-8">
											<input class="form-control" id="número de boleta" name="número de boleta" type="number" v-model="pago.numero_deposito" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('número de boleta')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label float-right" for="monto total">Monto total</label>
										<div class="col-sm-8">
											<input class="form-control" id="monto total" name="monto total" type="number" v-model="pago.monto_deposito" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('monto total')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label" for="deposito">Adjuntar foto de ultima lectura (.jpg)</label>
										<div class="col-sm-8">
											<input class="form-control" id="deposito" name="foto de boleta de depósito" type="file" v-validate="'ext:jpg,png'" v-on:change="getUrlLocalImage">
											<div class="invalid-feedback ">{{errors.first('foto de boleta de depósito')}}</div>
											<a v-bind:href="pago.deposito ? pago.deposito.replace('public', '/storage') : '#'" target="_blank" v-show="pago.deposito">Foto registrada en este pago</a>
											<div class="text-center">
												<img v-bind:src="pago.foto" class="img-fluid" alt="Foto de boleta de depósito" v-show="pago.foto">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label" for="banco">
											Banco
										</label>
										<div class="col-sm-8">
											<select class="form-control" id="banco" name="banco" type="number" v-model="pago.banco_id" v-validate="'required'">
												<option value=""></option>
												<option v-bind:value="banco.id" v-for="banco of bancos">{{banco.banco}}</option>

											</select>
											<div class="invalid-feedback ">{{errors.first('banco')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label" for="banco">
											Forma de pago
										</label>
										<div class="col-sm-8">
											<select class="form-control" id="forma de pago" name="forma de pago" type="number" v-model="pago.forma_pago_id" v-validate="'required'">
												<option value=""></option>
												<option v-bind:value="forma.id" v-for="forma of formas">{{forma.forma_pago}}</option>

											</select>
											<div class="invalid-feedback ">{{errors.first('forma de pago')}}</div>
										</div>
									</div>
									<button class="btn btn-primary" type="submit">
										<i class="fas fa-save fa-lg mr-2"></i>
										Actualizar
									</button>
								</form>
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
				bancos: [],
				formas: [],
				pago: {},
				message: {
					show: false
				},
				errors_server: []
			}
		},
		mounted() {
			axios.get('/api/bancos?paginate=no')
			.then(response => {
				this.bancos = response.data
			})
			axios.get('/api/formas-de-pago?paginate=no')
			.then(response => {
				this.formas = response.data
			})

			axios.get('/api/pagos/' + this.$route.params.id)
			.then(response => {
				this.pago = response.data
			})
			.catch(error => {
				if(error.response.status == 404) {
					this.message.show = true
					this.message.title = 'Pago no encontrado'
					this.message.body = `
					El pago con código <span class="font-weight-bold">${this.$route.params.id}</span> no existe en la base de datos`
				}
			})
		},
		methods: {
			getUrlLocalImage(e) {
				if(typeof e.target.files[0] != 'undefined') {
					this.pago.foto = URL.createObjectURL(e.target.files[0])
				}
			},
			onSubmit() {
				this.$validator.validate()
				.then(isValid => {
					this.errors_server = [];
					let formData = new FormData();

					formData.append('fecha_deposito', this.pago.fecha_deposito)
					formData.append('numero_deposito', this.pago.numero_deposito)
					formData.append('monto_deposito', this.pago.monto_deposito)
					if(typeof document.getElementById('deposito').files[0] != 'undefined') {
						formData.append('deposito', document.getElementById('deposito').files[0])
					}
					formData.append('contador_id', this.pago.id)

					formData.append('banco_id', this.pago.banco_id)
					formData.append('forma_pago_id', this.pago.forma_pago_id)

					axios.post(`/api/pagos/${this.pago.id}`, formData, {
						headers:{
							'Accept': 'application/json',
							'Content-Type' : 'multipart/form-data',
						}
					})
					.then(response => {
						Swal.fire({
							title: 'Registro exitoso',
							html: 'Pago registrado correctamente',
							icon: 'success'
						})
						.then(result => {
							this.pago = response.data
						})
					})
					.catch(error => {
						if(error.response.data.exception == 'Illuminate\\Database\\QueryException') {
							Swal.fire({
								title: 'Error',
								html: `El pago que está intentando registrar ya fue guardado con anterioridad`,
								icon: 'error'
							})
						}else if (typeof error.response.data === 'object') {
							this.errors_server = _.flatten(_.toArray(error.response.data.errors))
						} else {
							this.errors_server = [error.response.data]
						}
					})
				})
			}
		}
	}
</script>