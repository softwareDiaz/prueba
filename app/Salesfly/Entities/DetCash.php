<?php
namespace Salesfly\Salesfly\Entities;

class DetCash extends \Eloquent {

	protected $table = 'detCash';
    
    protected $fillable = ['fecha',
    						'hora',
    						'montoCaja',
    						'montoMovimientoTarjeta',
                            'montoMovimientoEfectivo',
    						'montoFinal',
    						'estado',
    						'observacion',
    						'cashMotive_id',
    						'cash_id',
                            'otherPhead_id',
                            'expense_id'];
    public function cashMotive(){
        return $this->belongsTo('Salesfly\Salesfly\Entities\CashMotive','cashMotive_id');
    }
    public function cash(){
        return $this->belongsTo('Salesfly\Salesfly\Entities\Cash','cash_id');
    }
}