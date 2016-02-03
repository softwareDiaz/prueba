<?php

namespace Salesfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Salesfly\Salesfly\Repositories\SaleDetPaymentRepo;
use Salesfly\Salesfly\Managers\SaleDetPaymentManager;
use Salesfly\Salesfly\Repositories\SalePaymentRepo;
use Salesfly\Salesfly\Managers\SalePaymentManager;

use Salesfly\Salesfly\Repositories\CashRepo;
use Salesfly\Salesfly\Managers\CashManager;

use Salesfly\Salesfly\Repositories\DetCashRepo;
use Salesfly\Salesfly\Managers\DetCashManager;

use Salesfly\Salesfly\Repositories\SaleRepo;
use Salesfly\Salesfly\Managers\SaleManager;

use Salesfly\Salesfly\Repositories\SeparateSaleRepo;
use Salesfly\Salesfly\Managers\SeparateSaleManager;

use Salesfly\Salesfly\Repositories\OrderSaleRepo;
use Salesfly\Salesfly\Managers\OrderSaleManager;

class SaleDetPaymentController extends Controller {

    protected $saleDetPaymentRepo;
    //protected $salePaymentRepo;

    //public function __construct(SaleDetPaymentRepo $saleDetPaymentRepo)
    //{
      //  $this->saleDetPaymentRepo = $saleDetPaymentRepo;
        //$this->salePaymentRepo = $salePaymentRepo;
    //}

    public function __construct(CashRepo $cashRepo,DetCashRepo $detCashRepo,SaleDetPaymentRepo $saleDetPaymentRepo,SalePaymentRepo $salePaymentRepo)
    {
        $this->saleDetPaymentRepo = $saleDetPaymentRepo;
        $this->salePaymentRepo=$salePaymentRepo;
        $this->detCashRepo=$detCashRepo;
        $this->cashRepo=$cashRepo;
        //$this->cashMonthlyRepo=$cashMonthlyRepo;
        //$this->yearRepo=$yearRepo;
        //$this->pendientAccountRepo=$pendientAccountRepo;
    }



    public function paginatep(){
        $persons = $this->saleDetPaymentRepo->paginate(5);
        return response()->json($persons);
    }
    

    public function searchDetalle($id)
    {
        //$q = Input::get('q');
        $saleDetPayment = $this->saleDetPaymentRepo->searchDetalle($id);

        return response()->json($saleDetPayment);
    }

     public function create(Request $request)
    {
     
      \DB::beginTransaction();

        $var=$request->detPayments;
        //var_dump("Hola"); die();
        $detPayment = $this->saleDetPaymentRepo->getModel();
        //$cashMonthly=$this->cashMonthlyRepo->getModel();
        $detCash=$this->detCashRepo->getModel();

        $cash =$this->cashRepo->find($request->cash_id);
        $payment = $this->salePaymentRepo->find($request->id);
        $update = new SalePaymentManager($payment,$request->only("Acuenta","Saldo"));
        $update->save();
        $var['tipoPago']='P';
        //$verDeudas=$this->pendientAccountRepo->verSaldos($request->input("supplier_id"));

        
     //if(intval($request->input('cash_id'))>0){

        $detcash = new DetCashManager($detCash,$request->all());
        $detcash->save();
        $var['detCash_id']=$detCash->id;
             $request->merge(["ingresos"=>floatval($cash->ingresos)+floatval($var["monto"])]);
             $request->merge(['fechaInicio'=>$cash->fechaInicio]);
             $request->merge(['fechaFin'=>$cash->fechaFin]);
             $request->merge(['montoInicial'=>$cash->montoInicial]);
             $request->merge(['gastos'=>$cash->gastos]);
             $request->merge(['montoBruto'=>floatval($cash->montoBruto)+floatval($var["monto"])]);
             $request->merge(['montoReal'=>$cash->montoReal]);
             $request->merge(['descuadre'=>$cash->descuadre]);
             $request->merge(['estado'=>$cash->estado]);
             $request->merge(['notas'=>$cash->notas]);
             $request->merge(['cashHeader_id'=>$cash->cashHeader_id]);
             if($cash->user_id==auth()->user()->id  && $cash->estado==1){
              $request->merge(['user_id'=>$cash->user_id]);
             }else{
              return response()->json(['estado'=>'Usted no tiene permisos sobre esta caja o la caja esta cerrada??']);
             }
        $cashr = new CashManager($cash,$request->all());
        $cashr->save();
    //}
    /*if($request->input('cajamensual')==true){

    $request->merge(["amount"=>$var["montoPagado"]]);
    $request->merge(['descripcion'=>"Pago a Proveedores"]);
    $request->merge(['expenseMonthlys_id'=>1]);

    $request->merge(['fecha'=>$request->detPayments["fecha"]]);
    $cashMontl = new CashMonthlyManager($cashMonthly,$request->all());
    $cashMontl->save();
    $var['cashMonthly_id']=$cashMonthly->id;
}*/
    $manager = new SaleDetPaymentManager($detPayment,$var);
    $manager->save();
    $idpago=$detPayment->id;

    $monto=0;
    foreach($this->saleDetPaymentRepo->consultDetpayments($request->id) as $object){
        $monto=$monto+floatval($object["monto"]);
    }
    $payment1 = $this->salePaymentRepo->find($request->id);
    $request->merge(["Acuenta"=>$monto]);
    if(floatval($payment1->MontoTotal)-$monto>=0){
          $request->merge(["Saldo"=>floatval($payment1->MontoTotal)-$monto]);
    }else{
          return response()->json('error');
    }
    $manager = new SalePaymentManager($payment1,$request->only("Acuenta","Saldo"));
    $manager->save();
        \DB::commit();
        return response()->json(['estado'=>true,'id'=>$idpago,'montoP'=>$detPayment->Acuenta]);
    }

    //------------------------------------------------------
    
    public function find($id)
    {
        $detPayment = $this->saleDetPaymentRepo->mostrarDetPayment($id);
        return response()->json($detPayment);
    }

    public function edit(Request $request)
    {
        $detPayment = $this->detPaymentRepo->find($request->id);
        $manager = new DetPaymentManager($detPayment,$request->all());
        $manager->save();
        return response()->json(['estado'=>true, 'nombre'=>$detPayment->nombre]);
    }
//------------------------------------------------------
}