<template>
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Modificar consumo</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">
								Modificar consumo
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
										<label class="col-sm-3 col-form-label float-right" for="fecha de emisión">Fecha de emisión</label>
										<div class="col-sm-9">
											<input class="form-control" id="fecha de emisión" name="fecha de emisión" type="date" v-model="consumo.fecha_emision" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('fecha de emisión')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="mes consumo">
											Mes
										</label>
										<div class="col-sm-9">
											<select class="form-control" id="mes consumo" name="mes consumo" type="number" v-model="consumo.mes" v-validate="'required'">
												<option value=""></option>
												<option value="1">1. enero</option>
												<option value="2">2. febrero</option>
												<option value="3">3. marzo</option>
												<option value="4">4. abril</option>
												<option value="5">5. mayo</option>
												<option value="6">6. junio</option>
												<option value="7">7. julio</option>
												<option value="8">8. agosto</option>
												<option value="9">9. septiembre</option>
												<option value="10">10. octubre</option>
												<option value="11">11. noviembre</option>
												<option value="12">12. diciembre</option>
											</select>
											<div class="invalid-feedback ">{{errors.first('mes consumo')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="año consumo">
											Año
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="año consumo" name="año consumo" type="number" v-model="consumo.anio" v-validate="'required|digits:4|min_value:2020'">
											<div class="invalid-feedback ">{{errors.first('año consumo')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="fecha última lectura">
											Fecha última lectura
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="fecha última lectura" name="fecha última lectura" type="date" v-model="consumo.fecha_ultima_lectura" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('fecha última lectura')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="lectura actual">
											Última lectura
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="última lectura" name="última lectura" placeholder="metros cúbicos" type="number" v-model="consumo.ultima_lectura" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('última lectura')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="fecha de lectura actual">
											Fecha de lectura actual
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="fecha de lectura actual" name="fecha de lectura actual" type="date" v-model="consumo.fecha_lectura_actual" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('fecha de lectura actual')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="lectura actual">
											Lectura actual
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="lectura actual" name="lectura actual" placeholder="metros cúbicos" type="number" v-model="consumo.lectura_actual" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('lectura actual')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="foto de última lectura">Foto de última lectura (.jpg)</label>
										<div class="col-sm-9">
											<input class="form-control" id="foto_lectura_actual" name="foto de última lectura" type="file" v-validate="'ext:jpg,png'" v-on:change="getUrlLocalImage">
											<div class="invalid-feedback ">{{errors.first('foto de última lectura')}}</div>
											<a v-bind:href="consumo.foto_lectura_actual ? consumo.foto_lectura_actual.replace('public', '/storage') : '#'" target="_blank" v-show="consumo.foto_lectura_actual">Foto registrada en este consumo</a>
											<div class="text-center">
												<img v-bind:src="consumo.foto" class="img-fluid" alt="Foto de lectura actual" v-show="consumo.foto">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="consumo">
											Consumo
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="consumo" name="consumo" type="number" v-model="consumo.consumo" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('consumo')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="monto_consumo">
											Consumo
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="monto_consumo" name="monto_consumo" type="number" v-model="consumo.monto_consumo" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('monto_consumo')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="exceso">
											Exceso
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="exceso" name="exceso" type="number" v-model="consumo.exceso">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="monto_exceso">
											Monto exceso
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="monto_exceso" name="monto_exceso" type="number" v-model="consumo.monto_exceso">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="fecha de vencimiento">
											Fecha de vencimiento
										</label>
										<div class="col-sm-9">
											<input class="form-control" id="fecha de vencimiento" name="fecha de vencimiento" type="date" v-model="consumo.fecha_pago" v-validate="'required'">
											<div class="invalid-feedback ">{{errors.first('fecha de vencimiento')}}</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label" for="estado">
											Estado
										</label>
										<div class="col-sm-9">
											<select class="form-control" id="estado" name="estado" type="number" v-model="consumo.estado" v-validate="'required'">
												<option value=""></option>
												<option value="P">Pagado</option>
												<option value="S">Sin pago</option>
											</select>
											<div class="invalid-feedback ">{{errors.first('estado')}}</div>
										</div>
									</div>
									<button class="btn btn-primary" type="submit">
										<i class="fas fa-save fa-lg mr-2"></i>
										Actualizar
									</button>
								</form>
								<div class="alert alert-danger mt-3" role="alert" v-show="errors_server.length > 0">
									<ul>
										<li v-for="error in errors_server">{{error}}</li>
									</ul>
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
				consumo: {},
				message: {
					show: false
				},
				errors_server: []
			}
		},
		mounted() {
			axios.get('/api/consumos/' + this.$route.params.id)
			.then(response => {
				this.consumo = response.data
			})
			.catch(error => {
				if(error.response.status == 404) {
					this.message.show = true
					this.message.title = 'Consumo no encontrado'
					this.message.body = `
					El consumo con código <span class="font-weight-bold">${this.$route.params.id}</span> no existe en la base de datos`
				}
			})
		},
		methods: {
			getUrlLocalImage(e) {
				if(typeof e.target.files[0] != 'undefined') {
					this.consumo.foto = URL.createObjectURL(e.target.files[0])
				} else {
					this.consumo.foto = null
				}
			},
			onSubmit() {
				this.$validator.validate()
				.then(isValid => {
					if(isValid) {
						let formData = new FormData();
						formData.append('fecha_emision', this.consumo.fecha_emision);
						formData.append('mes', this.consumo.mes)
						formData.append('anio', this.consumo.anio)
						formData.append('fecha_ultima_lectura', this.consumo.fecha_ultima_lectura)
						formData.append('ultima_lectura', this.consumo.ultima_lectura)
						formData.append('fecha_lectura_actual', this.consumo.fecha_lectura_actual)
						formData.append('lectura_actual', this.consumo.lectura_actual)
						if(typeof document.getElementById('foto_lectura_actual').files[0] != 'undefined') {
							formData.append('foto_lectura_actual', document.getElementById('foto_lectura_actual').files[0])
						}
						formData.append('consumo', this.consumo.consumo)
						formData.append('monto_consumo', this.consumo.monto_consumo)
						formData.append('exceso', this.consumo.exceso)
						formData.append('monto_exceso', this.consumo.monto_exceso)
						formData.append('fecha_pago', this.consumo.fecha_pago)
						formData.append('estado', this.consumo.estado)
						formData.append('contador_id', this.consumo.contador_id)

						axios.post(`/api/consumos/${this.consumo.id}`, formData, {
							headers:{
								'Accept': 'application/json',
								'Content-Type' : 'multipart/form-data',
							}
						})
						.then(response => {
							Swal.fire({
								title: 'Registro actualizado',
								html: 'El consumo actualizado correctamente',
								icon: 'success'
							})
							.then(result => {
								this.consumo = response.data
							})
						})
						.catch(error => {
							if(error.response.data.exception == 'Illuminate\\Database\\QueryException') {
								Swal.fire({
									title: 'Error',
									html: `El consumo que está intentando registrar ya fue guardado con anterioridad`,
									icon: 'error'
								})
							}else if (typeof error.response.data === 'object') {
								this.errors_server = _.flatten(_.toArray(error.response.data.errors))
							} else {
								this.errors_server = [error.response.data]
							}
						})
					}
				})
			}
		}
	}
</script>
