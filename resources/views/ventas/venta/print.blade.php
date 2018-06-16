<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>YOGGY</title>
        <div align="center">
            <h1>YOGGY</h1>
            <h3>FROZEN YOGURT</h3>
            <h3>Telefono:2460-0810</h3>
            <h3>CUIDAD QUESADA</h3>
            <h3>25 SUR BANCO POPULAR</h3>            
        </div>
------------------------------------------------------------------------------------------------------
    <div class="row">

         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Fecha: {{$venta->fecha_hora}}</label>               
            </div>
        </div> 
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="num_comprobante">#Factura: {{$venta->num_comprobante}}</label>
              
            </div>      
        </div>
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Cajero: {{ Auth::user()->name }}</label>               
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Cliente: {{$venta->nombre}}</label>               
            </div>
        </div>      
    </div>

    ------------------------------------------------------------------------------------------------------  
        

        <script type="text/javascript">
            function imprimir() {
                if (window.print) {
                    window.print();
                } else {
                    alert("La función de impresion no esta soportada por su navegador.");
                }


            }
        </script>
    </head>
    <body onload="imprimir();">
          <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">                
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if($detalles==null){
                 <h1>000</h1>
                }@else
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color:#A9D0F5">            
                            
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>P.Unitario</th>
                            <th>Descuento</th>
                            <th>SubTotal</th>
                                                  
                        </thead>
                        <tfoot>
                            
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>                            
                        </tfoot>
                        <tbody>
                            @foreach($detalles as $det)
                            <tr>
                                <td>{{$det->articulo}}</td>
                                <td>{{$det->cantidad}}</td>
                                <td>{{$det->precio_venta}}</td>
                                <td>{{$det->descuento}}</td> 
                                <td>{{$det->cantidad*$det->precio_venta-$det->descuento}}</td>              
                                 
                            </tr>                         
                            
                        </tbody>
                         @endforeach
                    </table>

                     <h4>SubTotal: {{$det->cantidad*$det->precio_venta-$det->descuento}}</h4>
                     <h4>Descuento: {{$det->descuento}}</h4>
                     <h3 id="total">Total: {{$venta->total_venta}}</h3>
                  
                    
                    @endif
                </div>
                <div align="center">
                <span  >Autorizado mediante Resolución 1197 DGTD según Gazeta 171 del 05/09/1997</span>    
                </div>

            </div>
            
        </div>
            
    </div>   
    </body>

     <p>

                <a class="btn btn-primary" href="{{url('ventas/venta/create')}}"><i class="fa fa-chevron-circle-left"></i> Nueva Factura</a>                        
            </p>
    
</html>