@extends ('layouts.admin')
@section ('contenido')  

      
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="proveedor"></label>
                <p></p>
            </div>
        </div>  
  
        
    </div>      
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">                
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if($pago_tarjeta==null)
                 <h1>Aún noy hay venta reportada</h1>
                @else
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color:#A9D0F5">   
                        	<th>Fecha</th>                         
                            <th>Comprobante</th>
                            <th>Total Venta</th>
                            <th>Baucher</th>                          
                        </thead>
                        <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>                                                      
                            <th>TOTAL<h4 id="total"></h4></th>
                                                    
                        </tfoot>
                        <tbody>
                            @foreach($pago_tarjeta as $pg)
                            <tr>
                            	<td>{{$pg->fecha_hora}}</td>
                                <td>{{$pg->num_comprobante}}</td>
                                <td>{{$pg->total_venta}}</td>
                                <td>{{$pg->vaucher}}</td>
                                
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                    
                    @endif
                </div>    

            </div>
            <a href=""><button class="btn btn-info">PDF</button></a>   
            
        </div>
            
    </div>              
@endsection  