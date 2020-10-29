<template>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Reporte de clientes morosos</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">							
							<li class="breadcrumb-item active">
								Reporte de clientes morosos
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary mb-3" v-on:click="obtenerMorosos">
							<i class="fas fa-sync fa-lg mr-2"></i>
							Actualizar listado
						</button>
						<div class="bs-callout bs-callout-success bg-white" v-show="total_morosos == 0">
							<h4>Sin clientes morosos</h4>
							En este momento no hay ningún consumo en estado <b>pendiente de pago</b>
						</div>
						<section v-show="total_morosos > 0">
							<div class="form-group">
								<input class="form-control" id="texto a buscar" placeholder="Filtrar por nombre o cui del cliente" type="text" v-model="busqueda">
							</div>
							<div class="table-responsive">
								<table class="table table-hover border">
									<thead class="thead-light">
										<tr>
											<th scope="col">Cui</th>
											<th scope="col">Cliente</th>
											<th scope="col">Celular</th>
											<th scope="col">Correo</th>
											<th scope="col">Correlativo</th>
											<th scope="col">Ubicación</th>
											<th scope="col">Período</th>
											<th scope="col">Total</th>
											<th scope="col">Fecha vencimiento</th>
											<th scope="col">Estado</th>
											<th scope="col"></th>
										</tr>
									</thead>
									<tbody class="bg-white">
										<tr v-for="moroso of filtrarMoroso">
											<th scope="row">{{moroso.cui}}</th>
											<td>{{moroso.nombres}} {{moroso.apellidos}}</td>
											<td>{{moroso.celular}}</td>
											<td>{{moroso.correo}}</td>
											<td>{{moroso.correlativo}}</td>
											<td>{{moroso.ubicacion}}</td>
											<td>{{meses[moroso.mes-1]}}-{{moroso.anio}}</td>
											<td>{{moroso.monto_consumo + moroso.monto_exceso}}</td>
											<td>{{moroso.fecha_pago}}</td>
											<td>{{moroso.estado}}</td>
											<td>
												<router-link :to="{name: 'modificar.consumo', params: {id: moroso.id}}">
													<i class="fas fa-edit fa-lg"></i>
												</router-link>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</section>
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
				busqueda: '',
				morosos: [],
				meses: [
				'enero',
				'febrero',
				'marzo',
				'abril',
				'mayo',
				'junio',
				'julio',
				'agosto',
				'septiembre',
				'octubre',
				'noviembre',
				'diciembre'
				]
			}
		},
		computed: {
			total_morosos() {
				return this.morosos.length
			},
			filtrarMoroso() {
				return this.morosos.filter((moroso) => (moroso.cui + ' ' + moroso.nombres +' '+ moroso.apellidos).toLowerCase().includes(this.busqueda.toLowerCase()))
			}
		},
		mounted() {
			this.obtenerMorosos()
		},
		methods: {
			obtenerMorosos() {
				axios.get('/api/clientes-morosos')
				.then(response => {
					this.morosos = response.data
				})
			}
		}
	}
</script>