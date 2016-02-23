<?php
namespace Salesfly\Salesfly\Entities;

class ServicePayment extends \Eloquent {

	protected $table = 'servicePayments';
    
    protected $fillable = ['fecha',
    						'monto',
    						'numCaja',
    						'tipoPago',
    						'service_id', 
    						'saleMethodPayment_id',
                            'detCash_id'];

    public function saleMethodPayment(){
        return $this->belongsTo('Salesfly\Salesfly\Entities\SaleMethodPayment','saleMethodPayment_id');
    }
}