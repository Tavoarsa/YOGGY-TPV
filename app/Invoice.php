<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
     protected $table ='invoice';
    protected $primaryKey= 'idinvoice';

    public $timestamps=false;

    protected $fillable= [
    	'iddetalle_venta',
    	'total_venta',
    	'pago_tarjeta',
    	'pago_efectivo',
    	'vuelto',
    	'vaucher'
    	
    ];

    protected $guarded = [

    ];
}
