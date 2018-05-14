<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentas\Http\Requests\IngresoFormRequest;
use sisVentas\Ingreso;
use sisVentas\DetalleIngreso;
use DB;
use Carbon\carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
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
    		$ingresos= DB::table('ingreso as i')
    						->join('persona as p','i.idproveedor','=','p.idpersona')
    						->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
    						->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado'
    							,DB::raw('sum(di.cantidad*precio_compra)as total'))
    						->where('i.num_comprobante', 'LIKE','%'.$query.'%')
    						->orderBy('i.idingreso','desc')
    						->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
    						->paginate(7);
    		return view('compras.ingreso.index',["ingresos"=>$ingresos, "searchText"=>$query]);
    		
    	}
    }

    public function create(){

    	$personas= DB::table('persona')->where('tipo_persona','=','Proveedor')->get();
    	//consulta a tabla de articulos, con el alias ar selecionamos y concatemos en una solo colunmo el nombre con el codigo del articulo
    	$articulos= DB::table('articulo as art')
    				->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo')
    				->where('art.estado','=','Activo')
    				->get();
    	return view('compras.ingreso.create',["personas"=>$personas, "articulos"=>$articulos]);
    }
      public function store(IngresoFormRequest $request){
      	//Tracsacciones
      	//para combrobar si se almacena en ambas tablas, si hay algun problema en alguna da un roolback
    	try{
    		DB::beginTransaction();
    		$ingreso=new Ingreso;
    		$ingreso->idproveedor = $request->get('idproveedor');
    		$ingreso->tipo_comprobante= $request->get('tipo_comprobante');
    		$ingreso->serie_comprobante=$request->get('serie_comprobante');
    		$ingreso->num_comprobante=$request->get('num_comprobante');
    		$mytime= Carbon::now('America/Costa_Rica');//obtener la fecha actual...
    		$ingreso->fecha_hora= $mytime->toDateTimeString();//convertir en formato fecha_hora
    		$ingreso->impuesto='13';
    		$ingreso->estado='A';
    		$ingreso->save();
    		//detalle: recibo un array: Formulario de registro de ingresos
    		$idarticulo=$request->get('idarticulo');//obtiene uno o varios articulos(array)
    		$cantidad=$request->get('cantidad');
    		$precio_compra=$request->get('precio_compra');
    		$precio_venta=$request->get('precio_venta');

    		$cont=0;
    		//mientras que sea menor a la cantidad de idarticulos que ingresen 	
    		while($cont < count($idarticulo)){
    			$detalle= new DetalleIngreso();
    			$detalle->idingreso =$ingreso->idingreso;//ingrso de mi objeto ingreso 
    			$detalle->idarticulo=$idarticulo[$cont];
    			$detalle->cantidad=$cantidad[$cont];
    			$detalle->precio_compra=$precio_compra[$cont];
    			$detalle->precio_venta=$precio_venta[$cont];
    			$detalle->save();
    			$cont=$cont + 1;
    		}
    		
    		DB::commit();
    	}catch(\Exeption $e){
    		DB::rollback();
    	}

    	return Redirect::to('compras/ingreso');
    }

    public function show($id){
    	//obtengo el ingreso por id
    	$ingreso=DB::table('ingreso as i')
    						->join('persona as p','i.idproveedor','=','p.idpersona')
    						->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
    						->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra)as total'))
    						->where('i.idingreso','=',$id)
    						->first();
    	//obtego los detalles segun el id del ingreso 					
    	$detalles=DB::table('detalle_ingreso as d')
    					->join('articulo as a','d.idarticulo','=','a.idarticulo')
    					->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta')
    					->where('d.idingreso','=',$id)
    					->get();
    	return view('compras.ingreso.show',["ingreso"=>$ingreso, "detalles"=>$detalles]);
    }

    public function destroy($id){

    	$ingreso= Ingreso::findOrFail($id);
    	$ingreso->Estado= 'C';
    	$ingreso->update();
    	return Redirect::to('compras/ingreso');
    }
}
