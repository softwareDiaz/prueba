<?php

namespace Salesfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Salesfly\Salesfly\Repositories\ServicePaymentRepo;
use Salesfly\Salesfly\Managers\ServicePaymentManager;

use Salesfly\Salesfly\Repositories\CashRepo;
use Salesfly\Salesfly\Managers\CashManager;

use Salesfly\Salesfly\Repositories\DetCashRepo;
use Salesfly\Salesfly\Managers\DetCashManager;

class ServicePaymentController extends Controller {

    protected $servicePaymentRepo;

    public function __construct(CashRepo $cashRepo,DetCashRepo $detCashRepo,ServicePaymentRepo $servicePaymentRepo)
    {
        $this->servicePaymentRepo = $servicePaymentRepo;
        $this->detCashRepo=$detCashRepo;
        $this->cashRepo=$cashRepo;
    }

    

    public function create(Request $request)
    {
     
      \DB::beginTransaction();

        $var=$request->detPayments;
        $detPayment = $this->servicePaymentRepo->getModel();
        
        $detCash=$this->detCashRepo->getModel();

        $cash =$this->cashRepo->find($request->cash_id);

        //$payment = $this->salePaymentRepo->find($request->id);
        //$update = new SalePaymentManager($payment,$request->only("Acuenta","Saldo"));
        //$update->save();
        $var['tipoPago']='A';

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
        $manager = new ServicePaymentManager($detPayment,$var);
        $manager->save();
        
        \DB::commit();
        return response()->json(['estado'=>true]);
    }

    public function find($id)
    {
        $detPayment = $this->servicePaymentRepo->consultServicePayments($id);
        return response()->json($detPayment);
    }

    public function edit(Request $request)
    {
        $brand = $this->servicePaymentRepo->find($request->id);
        //var_dump($brand);
        //die(); 
        $manager = new ServicePaymentManager($brand,$request->all());
        $manager->save();

        //Event::fire('update.brand',$brand->all());
        return response()->json(['estado'=>true, 'nombre'=>$brand->nombre]);
    }

    public function destroy(Request $request)
    {
        \DB::beginTransaction();
        $detPayment=$this->servicePaymentRepo->find($request->detpId);
        $pagoTemporal=$detPayment->monto;
        $detPayment->delete();
        //$MontotalTemp=$request->input('monto');
        //$AcuentaTemp=$request->input('Acuenta');
        
        //$request->merge(['Acuenta'=>$AcuentaTemp-$pagoTemporal]);
        //$AcuentaTemp2=$request->input('Acuenta');
        //$request->merge(['Saldo'=>$MontotalTemp-$AcuentaTemp2]);
        //$payment = $this->salePaymentRepo->find($request->id);
        //      $montoTotalPagado=0;
    //foreach($this->servicePaymentRepo->consultDetpayments($request->id) as $object){
      //  $montoTotalPagado=$montoTotalPagado+floatval($object["monto"]);
    //}
    //$request->merge(["Acuenta"=>$montoTotalPagado]);
    //if(floatval($payment->MontoTotal)-$montoTotalPagado>=0){
      //    $request->merge(["Saldo"=>floatval($payment->MontoTotal)-$montoTotalPagado]);
    //}else{
      //    return response()->json('error');
    //}
        //$manager = new SalePaymentManager($payment,$request->only("Acuenta","Saldo"));
        //$manager->save(); 
            
            $detcash=$this->detCashRepo->find($request->input("detCash_id"));
            $cash=$this->cashRepo->find($detcash->cash_id);
            
             $request->merge(["ingresos"=>floatval($cash->ingresos)-floatval($pagoTemporal)]);
             $request->merge(['montoBruto'=>floatval($cash->montoBruto)-floatval($pagoTemporal)]);
             $request->merge(['fechaInicio'=>$cash->fechaInicio]);
             $request->merge(['fechaFin'=>$cash->fechaFin]);
             $request->merge(['montoInicial'=>$cash->montoInicial]);
             $request->merge(['gastos'=>$cash->gastos]);
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
            $detcash->delete();
       \DB::commit();
        return response()->json(['estado'=>true]);
    }
}