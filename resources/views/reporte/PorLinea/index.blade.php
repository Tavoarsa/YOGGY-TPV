@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Articulos</h3>
		@include('reporte.PorLinea.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>					
					<th>Nombre</th>
					<th>Código</th>
					<th>Categoria</th>
					<th>Stock</th>					
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($articulos as $art)
				<tr>					
					<td>{{ $art->nombre}}</td>
					<td>{{ $art->codigo}}</td>
					<td>{{ $art->categoria}}</td>
					<td>{{ $art->stock}}</td>					
					<td>{{ $art->estado}}</td>
					<td>
						<a href="{{URL::action('ReporteController@show',$art->idarticulo)}}"><button class="btn btn-info">Reporte</button></a>
						<a href="{{URL::action('ReporteController@ventas_linea_pdf',$art->idarticulo)}}"><button class="btn btn-info">PDF</button></a>               
					</td>					
					
				</tr>				
				@endforeach
			</table>
		</div>
		{{$articulos->render()}}
	</div>
</div>

@endsection