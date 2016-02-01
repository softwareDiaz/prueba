<?php
namespace Salesfly\Salesfly\Entities;

class DetServices extends \Eloquent {

	protected $table = 'detServices';
    
    protected $fillable = ['precioProducto',
    						'precioVenta',
    						'cantidad',
    						'descuento',
    						'subTotal',
    						'service_id',
    						'detPre_id',
    						'listService_id'];
}