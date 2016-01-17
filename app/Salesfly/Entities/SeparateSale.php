<?php
namespace Salesfly\Salesfly\Entities;

class SeparateSale extends \Eloquent {

	protected $table = 'separateSales';
    
    protected $fillable = ['fechaPedido',
                            'fechaEntrega',
    						'montoTotal',
    						'montoBruto',
    						'descuento',
    						'fechaAnulado',
    						'customer_id',
    						'employee_id',
    						'estado',
    						'igv',
    						'notas']; 

    public function customer(){
        return $this->belongsTo('Salesfly\Salesfly\Entities\Customer','customer_id');
    }
    public function employee(){
        return $this->belongsTo('Salesfly\Salesfly\Entities\Employee','employee_id');
    }
}