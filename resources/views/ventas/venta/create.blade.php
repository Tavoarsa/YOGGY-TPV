@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nueva Venta</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

            @if (session('status'))
    <div class="alert alert-success">
       <h3> EL vuelto es: {{ session('status') }}</h3> 
    </div>
@endif
		</div>
	</div>

			{!!Form::open(array('url'=>'ventas/venta','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}
    <div class="row">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="form-group">
	    		<label for="proveedor">Cliente</label>
	            <select name="idcliente"  id="idcliente" class="form-control selectpicker"data-live-search="true">
	            	@foreach($personas as $persona)
	            	<option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
	            	@endforeach	            	
	            </select>
            </div>
    	</div>	

    	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
	    	<div class="form-group">
	    		<label for="tipo_comprobante">Comprobante</label>
	            <select name="tipo_comprobante" class="form-control">
	            	<option value="Boleta">Boleta</option>
	            	<option value="Factura" selected>Factura</option>
	            	<option value="Ticket">Ticket</option>
	            </select>
	        </div>    	
    	</div>
    	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    		<div class="form-group">
	    		<label for="serie_comprobante">Serie comprobante</label>
	            <input type="text" name="serie_comprobante" required value="0" class="form-control" placeholder="Serie comprobante...">
            </div>     	
    	</div> 

    	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    		<div class="form-group">
	    		<label for="num_comprobante">Número comprobante</label>
	            <input type="text" name="num_comprobante" required value="{{$num_comprobante->num_comprobante + 1}}"  readonly class="form-control" placeholder="Número comprobante...">
            </div>     	
    	</div>

    </div>    	
    <div class="row">
    	<div class="panel panel-primary">
    		<div class="panel-body">
    			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    				<div class="form-group">
    					<label>Artículo</label>
    					<select  name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true">
    						@foreach($articulos as $articulo)
    						<option value="{{$articulo->idarticulo}}_{{$articulo->stock}}_{{$articulo->precio_promedio}}">{{$articulo->articulo}}</option>
    						@endforeach
    					</select>
    				</div>
    			</div>
    			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
    				<div class="form-group">
    					<label for="cantidad">Cantidad</label>    					
    					<input type="number" name="pcantidad" id="pcantidad" class="form-control" placeholder="cantidad">
    				</div>
    			</div> 
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label for="stock">Stock</label>                      
                        <input type="number" disabled name="pstock" id="pstock" class="form-control" placeholder="Stock">
                    </div>
                </div>   		
    			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
    				<div class="form-group">
    					<label for="precio_venta">Precio Venta</label>    					
    					<input type="number" name="pprecio_venta" id="pprecio_venta" class="form-control" placeholder="Precio Venta">
    				</div>
    			</div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label for="descuento">Descuento</label>                     
                        <input type="number"  name="pdescuento" value="0" id="pdescuento" class="form-control" placeholder="Descuento">
                    </div>
                </div>

    			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
    				<div class="form-group">
    					<button type="button" id="bt_add" class="btn btn-primary">Agregar</button>    					
    				</div>
    			</div>
    			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    				<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
    					<thead style="background-color:#A9D0F5">    					
    						<th>Opciones</th>
    						<th>Artículo</th>
    						<th>Cantidad</th>    						
    						<th>Precio Venta</th>
                            <th>Descuento</th>
    						<th>Subtotal</th>    						
    					</thead>
    					<tfoot>
    						<th>TOTAL</th>
    						<th></th>
    						<th></th>
    						<th></th>
    						<th></th>
    						<th><h4 id="total">₡/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta"></th>     						
    					</tfoot>
    					<tbody>
    						
    					</tbody>
    				</table>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="guardar">
            <div class="form-group">
                
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalpago">Pagar</button>
                
            </div>      
        </div>  
    			</div>   
    		</div>


                <!-- Modal -->
            <div class="modal fade" id="modalpago" tabindex="-1" role="dialog" aria-labelledby="modalpago" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Metodo de Pago</h5>
                        <div class="panel-heading display-table" >                                       
                </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    <div class="modal-body">
              <div class="row">
                  <div class="col-xs-6">
                      <div class="well">
                         
                              <div class="panel-heading display-table" >
                                    <div class="row display-tr" >
                                        <h3 class="panel-title display-td" >Pago con tarjeta</h3>
                                        <div class="display-td" >                            
                                            <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
                                        </div>
                                    </div>                    
                             </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="baucher">Baucher</label>
                                            <div class="input-group">
                                                <input 
                                            type="tel"
                                            class="form-control"
                                            name="baucher"
                                            placeholder="Baucher"
                                            autocomplete="cc-number"
                                            autofocus 
                                        />
                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                </div>                            
                             </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="monto">Monto</label>
                                            <div class="input-group">
                                          <input type="number"  name="monto" value="0" id="monto" class="form-control" placeholder="Monto">
                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                </div>                            
                             </div>
                            </div>

                           
                        
                      </div>
                  </div>
                  <div class="col-xs-6">

                   
                              <div class="panel-heading display-table" >
                                    <div class="row display-tr" >
                                        <h3 class="panel-title display-td" >Efectivo</h3>
                                        <div class="display-td" >                            
                                            
                                        </div>
                                    </div>                    
                             </div>
                          <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="denominacion">Denominación</label>
                                            <div class="input-group">
                                                <input  type="number" id="denominacion"  class="form-control"  name="denominacion"   placeholder="Denominación"  autocomplete="cc-number" autofocus/>
                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
      
                                    </div>
                                </div>                            
                             </div>
                            </div>
                            <button id="btn1">Set Vuelto</button>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="vuelto">Vuelto</label>
                                            <div class="input-group">
                                             <input  type="number" id="vuelto"  class="form-control"  name="vuelto"   placeholder="Vuelto"  autocomplete="cc-number" autofocus/>
                                                                                  
                                                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                            </div>
                                    </div>                            
                                </div>
                            </div>

                  </div>
              </div>
          </div>
                      <div class="modal-footer">
                       <div class="form-group">
                <input name="_token" value="{{ csrf_token()}}" type="hidden"></input>
                <button  class="btn btn-primary" type="submit">Pagar</button>
                <button  id="btn_cancelar" class="btn btn-danger" type="reset">Cancelar</button>
            </div> 
                      </div>
                    </div>
                  </div>
                </div>
    	 	
    </div>              
        {!!Form::close()!!}
 @push('scripts')

 <script>

 $(document).ready(function(){

    $('#bt_add').click(function(){
    agregar();
    });

    $('#btn1').click(function(){
    vuelto();
    });


    
  });

 var cont=0;
 total=0;
 subtotal=[];
 $("#guardar").hide();
 $("#pidarticulo").change(mostrarValores);

 function mostrarValores(){

    datosArticulo=document.getElementById('pidarticulo').value.split('_');
    $("#pprecio_venta").val(datosArticulo[2]);
    $('#pstock').val(datosArticulo[1]);
 }

 function agregar(){

    datosArticulo=document.getElementById('pidarticulo').value.split('_');
    idarticulo=datosArticulo[0];
    articulo=$("#pidarticulo option:selected").text();
    cantidad=$("#pcantidad").val();
    descuento=$("#pdescuento").val();
    precio_venta=$("#pprecio_venta").val();
    stock=$("#pstock").val();

    if (idarticulo!="" && cantidad!="" && cantidad>0 && descuento!="" && precio_venta!="")
    {
        if(Number(stock)>=Number(cantidad)) {
 
            subtotal[cont]=(cantidad*precio_venta-descuento);
            total=total+subtotal[cont];

            var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="number" name="cantidad[]" value="'+cantidad+'"></td><td><input type="number" name="precio_venta[]" value="'+precio_venta+'"></td><td><input type="number" name="descuento[]" value="'+descuento+'"></td><td>'+subtotal[cont]+'</td></tr>';
            cont++;
            limpiar();
            $('#total').html("₡/ " + total);
            $('#total_venta').val(total);
            evaluar();
            $('#detalles').append(fila);
        }else{
            alert("La cantidad a vender es superior a existencia")
        }     
    }
    else
    {
      alert("Error al ingresar el detalle de la venta , revise los datos del articulo")
    }
  }

 function limpiar(){
    $("#pcantidad").val("");
    $("#pdescuento").val("");
    $("#pprecio_venta").val("");
  }

  function evaluar()
  {
    if (total>0)
    {
      $("#guardar").show();
    }
    else
    {
      $("#guardar").hide(); 
    }
   }

 function eliminar(index){
  total=total-subtotal[index]; 
    $("#total").html("₡/. " + total);
    $("#total_venta").val(total);     
    $("#fila" + index).remove();
    evaluar();
 }
 function vuelto(){
    var denominacion=document.getElementById("denominacion").value;
    var total_venta= document.getElementById("total_venta").value;

    var vuelto= parseFloat(denominacion) - parseFloat(total_venta);
    $("#vuelto").html("₡dd/. " + vuelto);
    console.log(vuelto);
 }

</script>

 @endpush	
@endsection