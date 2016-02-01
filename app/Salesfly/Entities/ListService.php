<?php
namespace Salesfly\Salesfly\Entities;

class ListService extends \Eloquent {

	protected $table = 'listServices';
    
    protected $fillable = ['nomServicio',
    						'descripcion',
    						'tipo',
    						'estado',
    						'store_id',
    						'costoAprox'];
}