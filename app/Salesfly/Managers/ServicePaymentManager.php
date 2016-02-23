<?php

namespace Salesfly\Salesfly\Managers;
class ServicePaymentManager extends BaseManager {

    public function getRules()
    {
        $rules = [ 
                    'fecha'=>'',
                    'monto'=>'',
                    'numCaja'=>'',
                    'tipoPago'=>'',
            'service_id' => '',
            'saleMethodPayment_id' => '',
            'detCash_id'=> ''
                  ];
        return $rules;
    }
} 