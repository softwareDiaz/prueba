<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\SaleDetPayment;

class SaleDetPaymentRepo extends BaseRepo{
    
    public function getModel()
    {
        
        return new SaleDetPayment;
    }

    public function search($q)
    {
        $saleDetPayment =SaleDetPayment::where('tipo','=', $q)
                    //with(['customer','employee'])
                    ->paginate(5);
        return $saleDetPayment;
    } 
    public function searchDetalle($id)
    {
        $salePayment =SaleDetPayment::leftjoin("detCash","detCash.id","=","saledetPayments.detCash_id")
                        ->leftjoin("cashes","cashes.id","=","detCash.cash_id")
                        ->leftjoin("cashHeaders","cashHeaders.id","=","cashes.cashHeader_id")
                        ->select('saledetPayments.*','cashHeaders.id as cashHeaders_id')
                        ->with('saleMethodPayment')
                        ->where('salePayment_id','=', $id.'%');
                        //->select('salePayments.*','cashHeaders.id')
                    //with(['customer','employee'])
                    
        return $salePayment->paginate(500);
    }
    function mostrarDetPayment($id){
        $detPayment=SaleDetPayment::leftjoin("detCash","detCash.id","=","saledetPayments.detCash_id")
            ->leftjoin("cashes","cashes.id","=","detCash.cash_id")
            ->leftjoin("cashHeaders","cashes.cashHeader_id","=","cashHeaders.id")
            ->leftjoin("methodPayments","methodPayments.id","=",
            "saledetPayments.saleMethodPayment_id")
            ->select("cashes.cashHeader_id as cashID","cashHeaders.nombre","saledetPayments.*",
            "methodPayments.nombre as nameMethod")->where('saledetPayments.salePayment_id','=',$id)
            ->get();
        return $detPayment;
    }
    public function paginate($count){
        $cashMonthlys = SaleDetPayment::with('saleMethodPayment')
                            ->leftjoin("detCash","detCash.id","=","saledetPayments.detCash_id")
                        ->leftjoin("cashHeaders","cashHeaders.id","=","detCash.cash_id")
                        ->select('saledetPayments.*','cashHeaders.id as ddd');
                            //->where('salePayment_id','=', '6');
        return $cashMonthlys->paginate($count);
    }
    function consultDetpayments($id){
        $detPayment=SaleDetPayment::where("saledetPayments.salePayment_id","=",$id)
                              ->get();

        return $detPayment;
    }
    

} 