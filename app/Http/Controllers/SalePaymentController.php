<?php

namespace Salesfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Salesfly\Salesfly\Repositories\SalePaymentRepo;
use Salesfly\Salesfly\Managers\SalePaymentManager;

use Salesfly\Salesfly\Repositories\SaleDetPaymentRepo;
use Salesfly\Salesfly\Managers\SaleDetPaymentManager;

use Salesfly\Salesfly\Repositories\CashRepo;
use Salesfly\Salesfly\Managers\CashManager;

use Salesfly\Salesfly\Repositories\DetCashRepo;
use Salesfly\Salesfly\Managers\DetCashManager;

class SalePaymentController extends Controller {

    protected $salePaymentRepo;

    public function __construct(SalePaymentRepo $salePaymentRepo,SaleDetPaymentRepo $saleDetPaymentRepo,DetCashRepo $detCashRepo,cashRepo $cashRepo)
    {
        $this->salePaymentRepo = $salePaymentRepo;
        $this->saleDetPaymentRepo = $saleDetPaymentRepo;
        $this->detCashRepo=$detCashRepo;
        $this->cashRepo=$cashRepo;
    }

    public function searchPayment($id)
    {
        //$q = Input::get('q');
        $detOrder = $this->salePaymentRepo->searchPayment($id);

        return response()->json($detOrder);
    } 
    public function find($id)
    {
        $payment = $this->salePaymentRepo->paymentById($id);
        return response()->json($payment);
    }
    public function searchPaymentOrder($id)
    {
        //$q = Input::get('q');
        $detOrder = $this->salePaymentRepo->searchPaymentOrder($id);

        return response()->json($detOrder);
    }
    public function searchPaymentSeparate($id)
    {
        //$q = Input::get('q');
        $detOrder = $this->salePaymentRepo->searchPaymentSeparate($id);

        return response()->json($detOrder);
    }
    /*public function edit(Request $request)
    {
        //var_dump($request->all());die();
        $payment = $this->salePaymentRepo->find($request->id);
        $manager = new SalePaymentManager($payment,$request->all());
        $manager->save();

        return response()->json(['estado'=>true, 'nombre'=>$payment->nombre]);
    }*/

    public function edit(Request $request)
    {

       // \DB::beginTransaction();
        $var=$request->detPayments;
        $detPayment= $this->SaleDetPaymentRepo->find($request->detpId);
         $pagoTemporal=$detPayment->monto;
        $detpay = new SaleDetPaymentManager($detPayment,$var);
        $detpay->save();
       
       
        $payment = $this->salePaymentRepo->find($request->id);
             
        $montoTotalPagado=0;
    foreach($this->SaleDetPaymentRepo->consultDetpayments($request->id) as $object){
        $montoTotalPagado=$montoTotalPagado+floatval($object["monto"]);
    }
    $request->merge(["Acuenta"=>$montoTotalPagado]);
    if(floatval($payment->MontoTotal)-$montoTotalPagado>=0){
          $request->merge(["Saldo"=>floatval($payment->MontoTotal)-$montoTotalPagado]);
    }else{
          return response()->json('error');
    }
    $manager = new SalePaymentManager($payment,$request->only("Acuenta","Saldo"));
    $manager->save();
        /*if(intval($request->input('Saldo_F'))>0){
                     $SaldosTemporales =$this->pendientAccountRepo->find2($detPayment['Saldo_F']);
                     if($SaldosTemporales!=null){
                     $request->merge(['Saldo'=>floatval($SaldosTemporales->Saldo)+floatval($pagoTemporal)]);
                     $request->merge(['Saldo'=>floatval($request->input('Saldo'))-floatval($detPayment['montoPagado'])]);
                     $request->merge(['orderPurchase_id'=>$SaldosTemporales->orderPurchase_id]);
                      $request->merge(['supplier_id'=>$SaldosTemporales->supplier_id]);
                     $request->merge(['estado'=>0]);
                     $request->merge(["fecha"=>$SaldosTemporales->fecha]);
                     $insercount=new PendientAccountManager($SaldosTemporales,$request->all());
                     $insercount->save();
                    }
                 
        } */   
      //=====================================================================
 //if(intval($request->input('detCash_id'))>0){
      $detCash=$this->detCashRepo->find($request->input('detCash_id'));
        $cash =$this->cashRepo->find($detCash["cash_id"]);
        $request->merge(['montoCaja'=>floatval($cash->montoBruto)-floatval($pagoTemporal)]);
        if (floatval($var["saleMethodPayment_id"])==1) {
            $request->merge(['montoMovimientoEfectivo'=>floatval($var["monto"])]);
            $totalCajaACtual=$request->input('montoMovimientoEfectivo');
             $request->merge(['montoMovimientoTarjeta'=>0]);
        }else{
            $request->merge(['montoMovimientoTarjeta'=>floatval($var["monto"])]);
            $totalCajaACtual=$request->input('montoMovimientoTarjeta');
             $request->merge(['montoMovimientoEfectivo'=>0]);   
        }  

        $request->merge(['montoFinal'=>floatval($request->input('montoCaja'))-floatval($totalCajaACtual)]);
        $request->merge(['montoBruto'=>floatval($request->input("montoFinal"))]);
        $request->merge(["gastos"=>floatval($cash->gastos)-floatval($pagoTemporal)]);
        $request->merge(["gastos"=>floatval($request->input("gastos")+floatval($var["montoPagado"]))]);
             $request->merge(['fecha'=>$detCash->fecha]);
             //$request->merge(['montoMovimientoTarjeta'=>0]);
             $request->merge(['hora'=>$detCash->hora]);
             $request->merge(['estado'=>1]);
             $request->merge(['cashMotive_id'=>$detCash->cashMotive_id]);
             if($cash->user_id==auth()->user()->id  && $cash->estado==1){
              $request->merge(['user_id'=>$cash->user_id]);
             }else{
              return response()->json(['estado'=>'Usted no tiene permisos sobre esta caja o la caja esta cerrada??']);
             }
        $detcash = new DetCashManager($detCash,$request->all());
        $detcash->save();
            $request->merge(['fechaInicio'=>$cash->fechaInicio]);
             $request->merge(['fechaFin'=>$cash->fechaFin]);
             $request->merge(['montoInicial'=>$cash->montoInicial]);
             $request->merge(['ingresos'=>$cash->ingresos]);
             $request->merge(['montoReal'=>$cash->montoReal]);
             $request->merge(['descuadre'=>$cash->descuadre]);
             $request->merge(['estado'=>$cash->estado]);
             $request->merge(['notas'=>$cash->notas]);
             $request->merge(['cashHeader_id'=>$cash->cashHeader_id]);
        $cashr = new CashManager($cash,$request->all());
        $cashr->save();
//}
//if(intval($request->input('cashMonthly_id'))>0){
        
    $cashMontly=$this->cashMonthlyRepo->find($request->input("cashMonthly_id"));
    $request->merge(["years_id"=>$cashMontly->years_id]);
    $request->merge(["amount"=>$var["montoPagado"]]);
    $request->merge(['descripcion'=>"Pago a Proveedores"]);
    $request->merge(['expenseMonthlys_id'=>1]);
    $request->merge(['fecha'=>$var["fecha"]]);
    $request->merge(['months_id'=>$cashMontly->months_id]);

    $cashMontl = new CashMonthlyManager($cashMontly,$request->all());
    $cashMontl->save();

//}
//\DB::commit();
        //==============================
        return response()->json(['estado'=>true, 'nombre'=>$payment->nombre]); 
    }

    public function editdetpatmentSale(Request $request)
    {
        $payment=$this->saleDetPaymentRepo->find($request->id);
        $diferenciaPago=($payment->monto)-($request->monto);

        $manager = new SaleDetPaymentManager($payment,$request->all());
        $manager->save();
        //-----------------------------------------------
        $cash = $this->cashRepo->find($request->numCaja);

        $cash['montoBruto']=$cash['montoBruto']-$diferenciaPago;
        $cash['ingresos']=$cash['ingresos']-$diferenciaPago;
        //var_dump($cash);die();
        $cash->save();
        //-----------------------------------------------
        //-----------------------------------------------
        $detcash = $this->detCashRepo->find($request->detCash_id);
        if ($request->saleMethodPayment_id=='1') {
            $detcash['montoMovimientoEfectivo']=$request->monto;
            $detcash['montoMovimientoTarjeta']=0;
        }else{
            $detcash['montoMovimientoEfectivo']=0;
            $detcash['montoMovimientoTarjeta']=$request->monto;  
        }
        $detcash->save();
        //var_dump($cash);die();
        $cash->save();
        //-----------------------------------------------
        $deetpayment = $this->salePaymentRepo->find($request->salePayment_id);

        $deetpayment['Acuenta']=$deetpayment['Acuenta']-$diferenciaPago;
        $deetpayment['Saldo']=$deetpayment['Saldo']+$diferenciaPago;
        
        $deetpayment->save();

        return response()->json(['estado'=>true, 'nombre'=>$payment->nombre]);

    }
    public function destroy(Request $request)
    {
        \DB::beginTransaction();
        $detPayment=$this->saleDetPaymentRepo->find($request->detpId);
        $pagoTemporal=$detPayment->montoPagado;
        $detPayment->delete();
        $MontotalTemp=$request->input('monto');
        $AcuentaTemp=$request->input('Acuenta');
        
        $request->merge(['Acuenta'=>$AcuentaTemp-$pagoTemporal]);
        $AcuentaTemp2=$request->input('Acuenta');
        $request->merge(['Saldo'=>$MontotalTemp-$AcuentaTemp2]);
        $payment = $this->salePaymentRepo->find($request->id);
              $montoTotalPagado=0;
    foreach($this->saleDetPaymentRepo->consultDetpayments($request->id) as $object){
        $montoTotalPagado=$montoTotalPagado+floatval($object["monto"]);
    }
    $request->merge(["Acuenta"=>$montoTotalPagado]);
    if(floatval($payment->MontoTotal)-$montoTotalPagado>=0){
          $request->merge(["Saldo"=>floatval($payment->MontoTotal)-$montoTotalPagado]);
    }else{
          return response()->json('error');
    }
        $manager = new SalePaymentManager($payment,$request->only("Acuenta","Saldo"));
        $manager->save();
        /*if(intval($request->input('Saldo_F'))>0){
                     $SaldosTemporales =$this->pendientAccountRepo->find2($request->input('Saldo_F'));
                     if($SaldosTemporales!=null){
                     $request->merge(['Saldo'=>floatval($SaldosTemporales->Saldo)+floatval($pagoTemporal)]);
                     $request->merge(['orderPurchase_id'=>$SaldosTemporales->orderPurchase_id]);
                      //$request->merge(['supplier_id'=>$SaldosTemporales->supplier_id]);
                     $request->merge(['estado'=>0]);
                     $request->merge(["fecha"=>$SaldosTemporales->fecha]);
                     $insercount=new PendientAccountManager($SaldosTemporales,$request->all());
                     $insercount->save();
                    }
                 
        }+*/
        //if(intval($request->input('detCash_id'))==true){
            
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
        //}
            //no se
        //if(intval($request->input("cashMonthly_id"))>0){
          //   $cashMontl =$this->cashMonthlyRepo->find($request->input("cashMonthly_id"));
            // $cashMontl->delete();
       // }
       \DB::commit();
        return response()->json(['estado'=>true, 'nombre'=>$payment->nombre]);
    }
    /*
    public function destroy(Request $request) 
    {
        //var_dump($request->all());die();
        $detPayment=$this->saleDetPaymentRepo->find($request->detpayment_id);

        $pagoTemporal=$detPayment->monto;
        $detPayment->delete();

        $MontotalTemp=$request->input('MontoTotal');
        $AcuentaTemp=$request->input('Acuenta');
        
        $request->merge(['Acuenta'=>$AcuentaTemp-$pagoTemporal]);
        $AcuentaTemp2=$request->input('Acuenta');
        $request->merge(['Saldo'=>$MontotalTemp-$AcuentaTemp2]);

        $payment = $this->salePaymentRepo->find($request->id);

        $manager = new SalePaymentManager($payment,$request->only("Acuenta","Saldo"));
        $manager->save();

        if(intval($request->input('detCash_id'))==true){
            
            $detcash=$this->detCashRepo->find($request->input("detCash_id"));
            $cash=$this->cashRepo->find($detcash->cash_id);

            if($detcash->montoMovimientoTarjeta > 0 && $detcash->montoMovimientoEfectivo>0){
                if ($request->saleMethodPayment==1) {
                    $detcash->montoMovimientoEfectivo=0;
                }else{
                    $detcash->montoMovimientoTarjeta=0;
                }
                //$detcashedit=$this->detCashRepo->find($request->input("detCash_id"));

                //$manager1 = new DetCashManager($detcashedit,$request->$detcash);
                $detcash->save();
            }else{
                $detcash->delete();   
            }
            
             $request->merge(["gastos"=>$cash->gastos]);
             $request->merge(['montoBruto'=>floatval($cash->montoBruto)-floatval($pagoTemporal)]);
             $request->merge(['fechaInicio'=>$cash->fechaInicio]);
             $request->merge(['fechaFin'=>$cash->fechaFin]);
             $request->merge(['montoInicial'=>$cash->montoInicial]);
             $request->merge(['ingresos'=>floatval($cash->ingresos)-floatval($pagoTemporal)]);
             //$request->merge(['montoBruto'=>$cash->montoBruto]);
             $request->merge(['montoReal'=>$cash->montoReal]);
             $request->merge(['descuadre'=>$cash->descuadre]);
             $request->merge(['estado'=>$cash->estado]);
             $request->merge(['notas'=>$cash->notas]);
             $request->merge(['cashHeader_id'=>$cash->cashHeader_id]); 
            $cashr = new CashManager($cash,$request->all());
            $cashr->save();
            
        }
       
        return response()->json(['estado'=>true, 'nombre'=>$payment->nombre]);
    }*/
}