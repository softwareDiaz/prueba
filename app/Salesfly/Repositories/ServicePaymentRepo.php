<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\ServicePayment;

class ServicePaymentRepo extends BaseRepo{
    
    public function getModel()
    {
        
        return new ServicePayment;
    }


    function consultServicePayments($id){
        $sercivePayment=ServicePayment::where("servicePayments.service_id","=",$id)
                              ->get();

        return $sercivePayment;
    }  
    function consultDetpayments($id){
        $detPayment=SaleDetPayment::where("servicePayments.service_id","=",$id)
                              ->get();

        return $detPayment;
    }

} 