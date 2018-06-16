<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;

use sisVentas\Articulo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use Carbon\carbon;
use PDF;

class ReporteController extends Controller
{
    public function __construct(){

        $this->middleware('auth');   
	}

    public function index(Request $request){

    	//obtener los resgistros de la tabla de base datos
    	if($request){
    		//determinar el texto de busquedad para filtrar  articulos
    		$query=trim($request->get('searchText'));//parametro de busquedad por categoria.trim:quita espacios
    		$articulos=DB::table('articulo as a')//Asocia con las tabla categoria
		     ->join('categoria as c ','a.idcategoria','=','c.idcategoria')//la tabla articulo con el alias a, se une con la tabla categoria con el alias  c
		     ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.descripcion','a.imagen', 'a.estado')
			 ->where('a.nombre','LIKE','%' .$query. '%')
             ->orwhere('a.codigo','LIKE','%' .$query. '%')      		 
    		 ->orderBy('idarticulo','desc')
    		 ->paginate(7)	;//busca el texto
    		return view('reporte.PorLinea.index',["articulos"=>$articulos, "searchText"=>$query]);
    	}
    }
//Reporte por linea Diario
      public function show($id){
      
        $mytime= Carbon::now('America/Costa_Rica');//obtener la fecha y hora actual...
        $fecha= $mytime->format('Y-m-d');//obtemos solo fecha.
        //$Fecha de venta del $id a consultar.
         $today= array_add(['fecha'=>$fecha],'price',100);
         $date=$today['fecha'];

    	//obtego los detalles segun el id del ingreso 					
    	$detalles=DB::table('detalle_venta as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->join('venta as v','d.idventa','=','v.idventa')
    					 ->join('categoria as c ','a.idcategoria','=','c.idcategoria')//la tabla articulo con el alias a, se une con la tabla categoria con el alias  c
    					->select('d.idarticulo','a.nombre as articulo','c.nombre as categoria','d.cantidad','d.descuento','d.precio_venta','v.fecha_hora','v.fecha')
    					->where('a.idarticulo','=', $id)
    					->where('v.fecha','=',$date)    					
    					->get();

    	$total_venta=DB::table('detalle_venta as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->join('venta as v','d.idventa','=','v.idventa')
    					->select('d.idarticulo','a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta',DB::raw('sum(cantidad*precio_venta) AS total_venta'))
    					->where('a.idarticulo','=', $id)
    					->where('v.fecha','=',$date)    
    					->get();

    					//dd($detalles);
    				
    	
      //dd($date);
		return view('reporte.PorLinea.show',compact('total_venta','detalles','date'));
    	//return view('reporte.PorLinea.show',["total_venta1"=>$total_venta1, "detalles"=>$detalles]);

    }
    public function ventas_linea_pdf($id){

    	 $mytime= Carbon::now('America/Costa_Rica');//obtener la fecha y hora actual...
        $fecha= $mytime->format('Y-m-d');//obtemos solo fecha.
        //Fecha de venta del $id a consultar.
         $today= array_add(['fecha'=>$fecha],'price',100);
         $date=$today['fecha'];

    	//obtego los detalles segun el id del ingreso 					
    	$detalles=DB::table('detalle_venta as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->join('venta as v','d.idventa','=','v.idventa')
    					->select('d.idarticulo','a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta','v.fecha')
    					->where('a.idarticulo','=', $id)
    					->where('v.fecha','=',$date)    					
    					->get();

    	$total_venta=DB::table('detalle_venta as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->join('venta as v','d.idventa','=','v.idventa')
    					->select('d.idarticulo','a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta',DB::raw('sum(cantidad*precio_venta) AS total_venta'))
    					->where('a.idarticulo','=', $id)
    					->where('v.fecha','=',$date)    
    					->get();
    	$pdf= PDF::loadView('reporte.PorLinea.pdf',['date'=>$date,'detalles'=>$detalles,'total_venta'=>$total_venta]);//dd("g");
    	return $pdf->stream();
    }

    //Reporte Vnetas Totales al Dia 
    public function show_ventasT(){

    	$mytime= Carbon::now('America/Costa_Rica');//obtener la fecha y hora actual...
        $fecha= $mytime->format('Y-m-d');//obtemos solo fecha.
        //Fecha de venta del $id a consultar.
        $today= array_add(['fecha'=>$fecha],'price',100);
        $date=$today['fecha'];       
        	//obtego los detalles totales de venta					
    	$detalles=DB::table('detalle_venta as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->join('venta as v','d.idventa','=','v.idventa')
    					->join('categoria as c ','a.idcategoria','=','c.idcategoria')//la tabla articulo con el alias a, se une con la tabla categoria con el alias  c
    					->select('d.idarticulo','a.nombre as articulo','c.nombre as categoria','d.cantidad','d.descuento','d.precio_venta','v.fecha_hora','v.fecha')   				
    					->where('v.fecha','=',$date)    					
    					->get();
    	$total_venta=DB::table('detalle_venta as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->join('venta as v','d.idventa','=','v.idventa')
    					->select('d.idarticulo','a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta',DB::raw('sum(cantidad*precio_venta) AS total_venta'))		
    					->where('v.fecha','=',$date)    
    					->get();
    					//dd($detalles);
    	return view('reporte.ventas_totales.index',compact('total_venta','detalles','date'));

    }

        public function ventas_totales_pdf(){

       	$mytime= Carbon::now('America/Costa_Rica');//obtener la fecha y hora actual...
        $fecha= $mytime->format('Y-m-d');//obtemos solo fecha.
        //Fecha de venta del $id a consultar.
        $today= array_add(['fecha'=>$fecha],'price',100);
        $date=$today['fecha'];

        	//obtego los detalles totales de venta					
    	$detalles=DB::table('detalle_venta as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->join('venta as v','d.idventa','=','v.idventa')
    					->join('categoria as c ','a.idcategoria','=','c.idcategoria')//la tabla articulo con el alias a, se une con la tabla categoria con el alias  c
    					->select('d.idarticulo','a.nombre as articulo','c.nombre as categoria','d.cantidad','d.descuento','d.precio_venta','v.fecha_hora','v.fecha')   				
    					->where('v.fecha','=',$date)    					
    					->get();
    	$total_venta=DB::table('detalle_venta as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->join('venta as v','d.idventa','=','v.idventa')
    					->select('d.idarticulo','a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta',DB::raw('sum(cantidad*precio_venta) AS total_venta'))		
    					->where('v.fecha','=',$date)    
    					->get();

         return view('reporte.ventas_totales.pdf',['date'=>$date,'detalles'=>$detalles,'total_venta'=>$total_venta]);               

    	/*return PDF::loadView('reporte.ventas_totales.pdf',['date'=>$date,'detalles'=>$detalles,'total_venta'=>$total_venta])->save('/public/invoice.pdf');//dd("g");*/
    	
    }
    public function rep_dinero(){

    	$mytime= Carbon::now('America/Costa_Rica');//obtener la fecha y hora actual...
        $fecha= $mytime->format('Y-m-d');//obtemos solo fecha.
        //Fecha de venta del $id a consultar.
        $today= array_add(['fecha'=>$fecha],'price',100);
        $date=$today['fecha'];
        	//obtego los detalles totales de venta					
        $pago_tarjeta=DB::table('detalle_venta as d')    					
    					->join('venta as v','d.idventa','=','v.idventa')
    					->join('invoice as i ','d.iddetalle_venta','=','i.iddetalle_venta')//la tabla articulo con el alias a, se une con la tabla categoria con el alias  c
    					->select('d.cantidad','d.descuento','d.precio_venta','v.fecha_hora','v.fecha','v.num_comprobante','v.total_venta','i.vaucher')   				
    					->where('v.fecha','=',$date)    					
    					->get();dd($pago_tarjeta);
    	return view('reporte.dinero.index',compact('pago_tarjeta','date'));


    }

}
