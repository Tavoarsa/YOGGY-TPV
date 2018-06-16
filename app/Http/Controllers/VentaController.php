<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentas\Http\Requests\VentaFormRequest;
use sisVentas\Venta;
use sisVentas\DetalleVenta;
use sisVentas\Invoice;
use DB;
use Carbon\carbon;
use Response;
use Illuminate\Support\Collection;
use Session;


class VentaController extends Controller
{
     public function __construct(){

        $this->middleware('auth');   
    }
   
    public function index(Request $request){

    	//obtener los resgistros de la tabla de base datos
    	if($request){
    		//determinar el texto de busquedad para filtrar  
    		$query=trim($request->get('searchText'));//parametro de busquedad por categoria.trim:quita espacios
    		//unir la tabla ingreso con la tabla persona, y uni con la tabla detalleingreso.
    		$ventas= DB::table('venta as v')
    						->join('persona as p','v.idcliente','=','p.idpersona')
    						->join('detalle_venta as dv','v.idventa','=','dv.idventa')
    						->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
    						->where('v.num_comprobante', 'LIKE','%'.$query.'%')
    						->orderBy('v.idventa','desc')
    						->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado')
    						->paginate(7);
    		return view('ventas.venta.index',["ventas"=>$ventas, "searchText"=>$query]);
    		
    	}
    }

    public function create(){

    	$personas= DB::table('persona')->where('tipo_persona','=','Cliente')->get();
    	//consulta a tabla de articulos, con el alias ar selecionamos y concatemos en una solo colunmo el nombre con el codigo del articulo

    	//PARA CALCULAR EL PRECIO DE VENTA : SE REALIZA UN PROMEDIO DE PRECIOS DEL ARTICULOS INGRESADOS
    	//groupBy: por el calculo
    	$articulos=DB::table('articulo as art')
                    ->join('detalle_ingreso as di','art.idarticulo','=','di.idarticulo')
                    ->select(DB::raw('CONCAT(art.codigo," ",art.nombre) AS articulo'), 'art.idarticulo','art.stock',DB::raw('AVG(di.precio_venta) AS precio_promedio'))
                    ->where('art.estado','=','Activo')
                    ->where('art.stock','>','0')
                    ->groupBy('articulo','art.idarticulo','art.stock')
                    ->get();

        $num_comprobante=DB::table('venta')
                            ->orderBy('num_comprobante','DES')
                            ->first();
                            //dd($num_comprobante->num_comprobante);

                       
    	return view('ventas.venta.create',["personas"=>$personas, "articulos"=>$articulos,"num_comprobante"=>$num_comprobante]);
    }

    public function store(VentaFormRequest $request){
      	//Tracsacciones
      	//para combrobar si se almacena en ambas tablas, si hay algun problema en alguna da un roolback        
        
    	try{
    		DB::beginTransaction();
    		$venta=new Venta;
    		$venta->idcliente = $request->get('idcliente');
    		$venta->tipo_comprobante= $request->get('tipo_comprobante');
    		$venta->serie_comprobante=$request->get('serie_comprobante');
    		$venta->num_comprobante=$request->get('num_comprobante');
    		$venta->total_venta=$request->get('total_venta');
    		$mytime= Carbon::now('America/Costa_Rica');//obtener la fecha actual...
            $mydate=$mytime->format('Y-m-d');//obtemos solo fecha.
            //dd($mydate);           
    		$venta->fecha_hora= $mytime->toDateTimeString();//convertir en formato fecha_hora
            $venta->fecha= $mydate;
    		$venta->impuesto='13';
    		$venta->estado='A';
    		$venta->save();
    		//detalle: recibo un array: Formulario de registro de ingresos
    		$idarticulo=$request->get('idarticulo');//obtiene uno o varios articulos(array)
    		$cantidad=$request->get('cantidad');
    		$descuento=$request->get('descuento');
    		$precio_venta=$request->get('precio_venta');

    		$cont=0;
    		//mientras que sea menor a la cantidad de idarticulos que ingresen 	
    		while($cont < count($idarticulo)){
    			$detalle= new DetalleVenta();
    			$detalle->idventa =$venta->idventa;//ingreso de mi objeto ingreso 
    			$detalle->idarticulo=$idarticulo[$cont];
    			$detalle->cantidad=$cantidad[$cont];
    			$detalle->descuento=$descuento[$cont];
    			$detalle->precio_venta=$precio_venta[$cont];               
    			$detalle->save();
    			$cont=$cont + 1;


    		}

            $total_efectivo=$request->total_venta - $request->monto;        
            $vuelto=$request->denominacion-$total_efectivo ;
            $invoice= new Invoice();
            $invoice->iddetalle_venta=$detalle->iddetalle_venta;
            $invoice->total_venta=$request->total_venta;
            $invoice->pago_tarjeta=$request->monto;
            $invoice->pago_efectivo=$request->denominacion;
            $invoice->vuelto=$vuelto;
            $invoice->vaucher=$request->baucher;
            $invoice->save();
           // dd("good");
    		
    		DB::commit();
    	}catch(\Exeption $e){
    		DB::rollback();
    	} 

        $venta_id= DB::table('venta')->orderBy('idventa','desc')->first();


        $venta=DB::table('venta as v')
                            ->join('persona as p','v.idcliente','=','p.idpersona')
                            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
                            ->where('v.idventa','=',$venta_id->idventa)
                            ->first();
        
        //obtego los detalles segun el id del ingreso                   
        $detalles=DB::table('detalle_venta as d')
                        ->join('articulo as a','d.idarticulo','=','a.idarticulo')
                        ->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta')
                        ->where('d.idventa','=',$venta_id->idventa)
                        ->get();

//dd($venta);

      
        
    	    return view('ventas.venta.print',["venta"=>$venta, "detalles"=>$detalles]);
    }

    public function show($id){
    	//obtengo el ingreso por id

    	$venta=DB::table('venta as v')
    						->join('persona as p','v.idcliente','=','p.idpersona')
    						->join('detalle_venta as dv','v.idventa','=','dv.idventa')
    						->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
    						->where('v.idventa','=',$id)
    						->first();
        
    	//obtego los detalles segun el id del ingreso 					
    	$detalles=DB::table('detalle_venta as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta')
    					->where('d.idventa','=',$id)
    					->get();
       
                      //  dd($json_detalle);
    	return view('ventas.venta.show',["venta"=>$venta, "detalles"=>$detalles]);
    }

    public function destroy($id){

    	$venta= Venta::findOrFail($id);
    	$venta->Estado= 'C';
    	$venta->update();
    	return Redirect::to('ventas/venta');
    }
}
