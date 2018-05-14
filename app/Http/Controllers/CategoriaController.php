<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;

use sisVentas\Categoria;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Http\Requests\CategoriaFormRequest;
use DB;



class CategoriaController extends Controller
{
   public function __construct(){

        $this->middleware('auth');   
    }
    public function index(Request $request){

    	//obtener los resgistros de la tabla de base datos
    	if($request){
    		//determinar el texto de busquedad para filtrar  categorias
    		$query=trim($request->get('searchText'));//parametro de busquedad por categoria.trim:quita espacios
    		$categorias=DB::table('categoria')->where('nombre','LIKE','%' .$query. '%')
    										  ->where('condicion', '=', '1')
    										  ->orderBy('idcategoria','desc')
    										  ->paginate(7)	;//busca el texto
    		return view('almacen.categoria.index',["categorias"=>$categorias, "searchText"=>$query]);
    	}
    }
    public function create(){

    	return view("almacen.categoria.create");
    }
    public function store(CategoriaFormRequest $request){

    	$categoria=new Categoria;
    	$categoria->nombre=$request->get('nombre');
    	$categoria->descripcion=$request->get('descripcion');
    	$categoria->condicion='1';
    	$categoria->save();
    	return Redirect::to('almacen/categoria');//listado de todas las categorias
    }
    public function show($id){

    	return view ("almacen.categoria.show",["categoria"=>Categoria::findOrFail($id)]);//muestra la categoria especifica

    }
    public function  edit($id){

    		return view ("almacen.categoria.edit",["categoria"=>Categoria::findOrFail($id)]);//muestra la categoria especifica    	
    }

    public function update(CategoriaFormRequest $request, $id){

    	$categoria =Categoria::findOrFail($id);
    	$categoria->nombre=$request->get('nombre');
    	$categoria->descripcion=$request->get('descripcion');
    	$categoria->update();
    	return Redirect::to('almacen/categoria');
    }
    //Actualiza la codicion de categoria, si esta actica o no.
    public function destroy($id){

    	$categoria = Categoria::findOrFail($id);
    	$categoria->condicion='0';
    	$categoria->update();
    	return Redirect::to('almacen/categoria');

    }   
    
}
