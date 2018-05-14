<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;

use sisVentas\Articulo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentas\Http\Requests\ArticuloFormRequest;
use DB;


class ArticuloController extends Controller
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
    		return view('almacen.articulo.index',["articulos"=>$articulos, "searchText"=>$query]);
    	}
    }
    public function create(){

    	$categorias=DB::table('categoria')->where('condicion','=','1')->get();
    	return view("almacen.articulo.create",["categorias"=>$categorias]);
    }
    public function store(ArticuloFormRequest $request){

    	$articulo=new Articulo;
    	$articulo->idcategoria=$request->get('idcategoria');
    	$articulo->codigo=$request->get('codigo');
    	$articulo->nombre=$request->get('nombre');
    	$articulo->stock=$request->get('stock');
    	$articulo->descripcion=$request->get('descripcion');
    	$articulo->estado='Activo';

    	if(Input::hasFile('imagen')){
    		$file=Input::file('imagen');
    		$file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
    		$articulo->imagen= $file->getClientOriginalName();
    	}
    	$articulo->save();
    	return Redirect::to('almacen/articulo');//listado de todos los articulos
    }
    public function show($id){

    	return view ("almacen.articulo.show",["articulo"=>Articulo::findOrFail($id)]);//muestra el articulo especifico

    }
    public function  edit($id){

    	$articulo = Articulo::findOrFail($id);
    	$categorias=DB::table('categoria')->where('condicion','=','1')->get();

    	return view ("almacen.articulo.edit",["articulo"=>$articulo,"categorias"=>$categorias]);//muestra el articulo especifico  	
    }

    public function update(ArticuloFormRequest $request, $id){

    	$articulo =Articulo::findOrFail($id);
    	$articulo->idcategoria=$request->get('idcategoria');
    	$articulo->codigo=$request->get('codigo');
    	$articulo->nombre=$request->get('nombre');
    	$articulo->stock=$request->get('stock');
    	$articulo->descripcion=$request->get('descripcion');
    	$articulo->estado='Activo';

    	if(Input::hasFile('imagen')){
    		$file=Input::file('imagen');
    		$file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
    		$articulo->imagen= $file->getClientOriginalName();
    	}
    	$articulo->update();
    	return Redirect::to('almacen/articulo');
    }
    //Actualiza la codicion de categoria, si esta actica o no.
    public function destroy($id){

    	$articulo = Articulo::findOrFail($id);
    	$articulo->estado='Inactivo';
    	$articulo->update();
    	return Redirect::to('almacen/articulo');

    }  
}
