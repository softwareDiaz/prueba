<?php
namespace Salesfly\Salesfly\Entities;

class Service extends \Eloquent {

	protected $table = 'services';
    
    protected $fillable = ['numeroServicio',
    						'fechaServicio',
    						'tipo',
    						'cliente',
    						'ruc',
							'direcion',
    						'telefono',
    						'empresa',
    						'descripcion',
    						'modelo',
    						'serie',
    						'accesorios',
    						'diagnostico',
    						'accionCorrectiva',
    						'estado',
    						'customer_id',
    						'employee_id',
                            'ordenTrabajo',
                            'user_id'
    						];

}