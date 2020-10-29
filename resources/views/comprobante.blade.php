<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Comprobante</title>
	<link rel="stylesheet" href="/css/app.css">
	<style type="text/css" media="print">
		@media print {
			#noprint {display:none;}
		}
		li.borderless {
			border-top: 0 none;
		}
		.bg-white {
			background-color: #f8fafc !important;
		}
	</style>
</head>
<body>
	<div class="mt-5 container border p-4 rounded">
		<div class="row">
			<div class="col-md-4">
				<h1 class="text-danger">RECIBO DE PAGO</h1>
				{{$pago->created_at}}
			</div>
			<div class="col-md-4 text-center">
				<b>
					ASOCIACIÓN DE VECINOS <br>
					EL MANZANO LA LIBERTAD,
					Santa Catarina Pinula
				</b>
			</div>
			<div class="col-md-4 text-right">
				NO. RECIBO <br>
				<b>
					{{str_pad($pago->id, 8, "0", STR_PAD_LEFT)}}
				</b>
			</div>
		</div>
		<div class="row mt-5">
			<div class="col-md-12">
				<b>
					{{strtoupper($cliente->nombres . " " . $cliente->apellidos)}} <br>
				</b>
				{{$contador->ubicacion}}
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-md-12">
				<div class="table-responsive-sm">
					<table class="table table-borderless">
						<tbody>
							<tr>
								<td style="width: 75%">
									SERVICIO DE AGUA POTABLE DEL MES DE
									<b>
										{{$meses[$consumo->mes-1]}}-{{$consumo->anio}}
									</b>
								</td>
								<td align="right" style="width: 25%">
									{{$consumo->monto_consumo}}
								</td>
							</tr>
							<tr>
								<td style="width: 75%">
									SALDO ANTERIOR
								</td>
								<td align="right" style="width: 25%">
									0
								</td>
							</tr>
							<tr>
								<td style="width: 75%">
									EXCESO 20 QUETZALES POR M<sup>3</sup>
								</td>
								<td align="right" style="width: 25%">
									{{$consumo->monto_exceso}}
								</td>
							</tr>
							<tr>
								<td style="width: 75%">
									<b>TOTAL</b>
								</td>
								<td class="border-top border-bottom border-dark" align="right" style="width: 25%">
									<b>Q.{{$pago->monto_deposito}}</b>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="container mt-5 text-center" id="noprint">
		<a href="javascript:window.print()">
			<img src="/images/print-icon.png">
		</a>
	</div>
</body>
</html>