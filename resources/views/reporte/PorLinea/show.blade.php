@extends ('layouts.admin')
@section ('contenido')	

	@foreach($total_venta as $total_V)		
    <div class="row">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="form-group">
	    		<label for="proveedor">Articulo: {{$total_V->articulo}}</label>
	            <p></p>
            </div>
    	</div>	
   @endforeach  
    	
    </div>    	
    <div class="row">
    	<div class="panel panel-primary">
    		<div class="panel-body">    			
    			
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if($detalles==null)
                 <h1>Aún noy hay venta reportada</h1>
                @else
    				<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
    					<thead style="background-color:#A9D0F5">    						
    						<th>Fecha</th>
    						<th>Cantidad</th>
    						<th>Precio venta</th>
    						<th>Descuento</th>
    						<th>Subtotal</th>    						
    					</thead>
    					<tfoot>
    						<th></th>
    						<th></th>
    						<th></th>
    						<th></th>    						                          
    						<th>TOTAL<h4 id="total">{{$total_V->total_venta}}</h4></th>
                            						
    					</tfoot>
    					<tbody>
                            @foreach($detalles as $det)
                            <tr>
                                <td>{{$det->fecha_hora}}</td>
                                <td>{{$det->cantidad}}</td>
                                <td>{{$det->precio_venta}}</td>
                                <td>{{$det->descuento}}</td>
                                <td>{{$det->cantidad*$det->precio_venta-$det->descuento}}</td>
                            </tr>
                            @endforeach
    						
    					</tbody>
    				</table>
                    
                    @endif
    			</div>    

    		</div>
    		
    	</div>
    	   	
    </div>              
@endsection  