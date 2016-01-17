<?php

namespace Salesfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Salesfly\Salesfly\Repositories\SaleRepo;
use Salesfly\Salesfly\Managers\SaleManager;

use Salesfly\Salesfly\Repositories\OrderSaleRepo;
use Salesfly\Salesfly\Managers\OrderSaleManager;

use Salesfly\Salesfly\Repositories\DetOrderSaleRepo;
use Salesfly\Salesfly\Managers\DetOrderSaleManager;

use Salesfly\Salesfly\Repositories\SeparateSaleRepo;
use Salesfly\Salesfly\Managers\SeparateSaleManager;

use Salesfly\Salesfly\Repositories\DetSeparateSaleRepo;
use Salesfly\Salesfly\Managers\DetSeparateSaleManager;

use Salesfly\Salesfly\Repositories\DetSaleRepo;
use Salesfly\Salesfly\Managers\DetSaleManager;

use Salesfly\Salesfly\Repositories\CustomerRepo;
use Salesfly\Salesfly\Managers\CustomerManager;

use Salesfly\Salesfly\Repositories\CashRepo;
use Salesfly\Salesfly\Managers\CashManager;

use Salesfly\Salesfly\Repositories\PromocionRepo;
use Salesfly\Salesfly\Managers\PromocionManager;

use Salesfly\Salesfly\Managers\SalePaymentManager;
use Salesfly\Salesfly\Repositories\SalePaymentRepo;

use Salesfly\Salesfly\Managers\DetCashManager;
use Salesfly\Salesfly\Repositories\DetCashRepo;

use Salesfly\Salesfly\Managers\HeadInputStockManager;
use Salesfly\Salesfly\Repositories\HeadInputStockRepo;

use Salesfly\Salesfly\Managers\InputStockManager;
use Salesfly\Salesfly\Repositories\InputStockRepo;

use Salesfly\Salesfly\Managers\SaleDetPaymentManager;
use Salesfly\Salesfly\Repositories\SaleDetPaymentRepo;
use Salesfly\Salesfly\Managers\StockManager;
use Salesfly\Salesfly\Repositories\StockRepo;

use Salesfly\Salesfly\Repositories\CashHeaderRepo;

use Salesfly\Salesfly\Managers\HeadInvoiceManager;
use Salesfly\Salesfly\Repositories\HeadInvoiceRepo;
use Salesfly\Salesfly\Managers\DetailInvoiceManager;
use Salesfly\Salesfly\Repositories\DetailInvoiceRepo;
use Salesfly\Salesfly\Managers\FBnumberManager;
use Salesfly\Salesfly\Repositories\FBnumberRepo;
class SalesController extends Controller
{
    protected $saleRepo;

    public function __construct(SaleRepo $saleRepo,CashHeaderRepo $ashHeaderRepo,
      PromocionRepo $promocionRepo,HeadInvoiceRepo $headInvoiceRepo)
    {
        $this->saleRepo = $saleRepo;
        $this->ashHeaderRepo = $ashHeaderRepo;
        $this->promocionRepo = $promocionRepo;
        $this->headInvoiceRepo=$headInvoiceRepo;
    }

    public function all()
    {
        $orders = $this->saleRepo->paginate(15);
        return response()->json($orders);
        //var_dump($materials);
    }
     public function DatosDocumento($id){
       $orders = $this->headInvoiceRepo->DatosDocumento($id);
        return response()->json($orders);
     }
    public function paginatep(){
        $orders = $this->saleRepo->paginate(15);
        return response()->json($orders);
    }

    public function find($id)
    {
        $cash = $this->saleRepo->find($id);
        return response()->json($cash);
    }

    public function search($q)
    {
        $orders = $this->saleRepo->search($q);

        return response()->json($orders);
    }
    public function index()
    {
        return View('sales.index');
    }
    public function form_create()
    {
        return View('sales.form_create');
    }
    public function form_edit()
    {
        return View('sales.form_edit');
    }

    public function create(Request $request) 
        {
        //var_dump($request->all());die();
        \DB::beginTransaction();
        $vuelto=$request->input("vuelto");
        $orderSale = $this->saleRepo->getModel();
        $var = $request->detOrders;
        $payment = $request->salePayment;
        $saledetPayments = $request->saledetPayments;
        $cajaAct = $request->caja;
        //var_dump($cajaAct);die();

        //---create movimiento---
            $movimiento = $request->movimiento;
            $detCashrepo;
            //$movimiento['observacion']=$temporal;
            $detCashrepo = new DetCashRepo;
            $movimientoSave=$detCashrepo->getModel();
        
            $insertarMovimiento=new DetCashManager($movimientoSave,$movimiento);
            $insertarMovimiento->save();
            $detCash_id=$movimientoSave->id;
        $request->merge(["cash_id"=>$cajaAct['id']]);
        //$almacen_id=$var->input("idAlmacen");
        //$variante_id=$var->input("vari");
        $request->merge(['detCash_id'=> $detCash_id]);
        $manager = new SaleManager($orderSale,$request->all());
        $manager->save();
        //$codSale=$orderSale->id;
        /*
       if($this->purchaseRepo->validateDate(substr($request->input('fechaEntrega'),0,10))){
            $order->fechaEntrega = substr($request->input('fechaEntrega'),0,10);
        }else{
           
            $order->fechaEntrega = null;
        }*/ 
        $orderSale->save();

        $temporal=$orderSale->id;

    //---Autualizar Caja---
            
            
            $cashrepo;
            //$movimiento['observacion']=$temporal;
            $cashrepo = new CashRepo;
            $cajaSave=$cashrepo->getModel();
            
            $cash1 = $cashrepo->find($cajaAct["id"]);
            
            //var_dump($cash1["id"]);die();
        
            //$insertarMovimiento=new DetCashManager($movimientoSave,$movimiento);
            //$insertarMovimiento->save();

            //$stores = $this->storeRepo->find($request->id);
            //var_dump($cash1);die();

            $manager1 = new CashManager($cash1,$cajaAct);
            $manager1->save();
            

        //----------------
        $salePaymentrepo;
        $payment['sale_id']=$temporal;
        $salePaymentrepo = new SalePaymentRepo;
        $paymentSave=$salePaymentrepo->getModel();
        
        $insertarpayment=new SalePaymentManager($paymentSave,$payment);
        $insertarpayment->save();          
        $paymentSave->save();

        $temporal1=$paymentSave->id;
            //--------------------------
                $saledetPaymentrepo;
                foreach($saledetPayments as $object1){
                    $object1['salePayment_id'] = $temporal1;
                    $object1['detCash_id']=$detCash_id;

                    $saledetPaymentrepo = new SaleDetPaymentRepo;

                    $insertar=new SaleDetPaymentManager($saledetPaymentrepo->getModel(),$object1);
                    $insertar->save();
          
                    $saledetPaymentrepo = null;
                }
            //--------------------------
    if(!empty($request->input("comprobante"))){
            ///var_dump("kkdkdkkdsk");die();
            $headInvoiceRepo=new HeadInvoiceRepo;
            $headInvoice=$headInvoiceRepo->getModel();
            $customerRepo=new CustomerRepo;
            $direccion=$customerRepo->find($request->input("customer_id"));
            $fbnumberRepo=new FBnumberRepo;
            
            $ashHeaderRepo=$this->ashHeaderRepo->comprobarCaja($cash1["id"]);

            $numbers=$fbnumberRepo->find($ashHeaderRepo["id"]);
            $num=$fbnumberRepo->find($ashHeaderRepo["id"]);
            $cajaId=$ashHeaderRepo["id"];
            if($request->input("tipoDoc")=="F"){
                 $request->merge(["numero"=>(intval($num->numFactura)+1)]);
                 $request->merge(["numFactura"=>$request->input("numero")]);
                 $request->merge(["numTiketFactura"=>(intval($num->numTiketFactura)+1)]);
                 $request->merge(["cliente"=>$direccion["empresa"]]);
                 $request->merge(["direccion"=>$direccion["direccFiscal"]]);
                 $request->merge(["ruc"=>$direccion["ruc"]]);
                 $inputfbnumber=new FBnumberManager($numbers,$request->only("numFactura","numTiketFactura"));
                 $inputfbnumber->save();
            }elseif($request->input("tipoDoc")=="B"){
                 $request->merge(["cliente"=>$direccion["nombres"]." ".$direccion["apellidos"]]);
                 $request->merge(["direccion"=>$direccion["direccContac"]]);
                 $request->merge(["numero"=>(intval($num->numBoleta)+1)]);
                 $request->merge(["numBoleta"=>$request->input("numero")]);
                 $request->merge(["numTiketBoleta"=>(intval($num->numTiketBoleta)+1)]);
                 $request->merge(["ruc"=>$direccion["dni"]]);
                 $inputfbnumber=new FBnumberManager($numbers,$request->only("numBoleta","numTiketBoleta"));
                 $inputfbnumber->save();
            }
            if($request->input("tipoDoc")=="TF")
            {
                 $request->merge(["numero"=>(intval($num->numTiketFactura)+1)]);
                 $request->merge(["numTiketFactura"=>(intval($num->numTiketFactura)+1)]);
                 $request->merge(["cliente"=>$direccion["empresa"]]);
                 $request->merge(["direccion"=>$direccion["direccFiscal"]]);
                 $request->merge(["ruc"=>$direccion["ruc"]]);
                 $inputfbnumber=new FBnumberManager($numbers,$request->only("numTiketFactura"));
                 $inputfbnumber->save();
            }elseif($request->input("tipoDoc")=="TB")
            {
                 $request->merge(["cliente"=>$direccion["nombres"]." ".$direccion["apellidos"]]);
                 $request->merge(["direccion"=>$direccion["direccContac"]]);
                 $request->merge(["numero"=>(intval($num->numTiketFactura)+1)]);
                 $request->merge(["numTiketFactura"=>(intval($num->numTiketFactura)+1)]);
                 $request->merge(["dni"=>$direccion["dni"]]);
                 $inputfbnumber=new FBnumberManager($numbers,$request->only("numTiketFactura"));
                 $inputfbnumber->save();
            }
            
            
            $request->merge(["subTotal"=>$request->input("montoBruto")]);
            $request->merge(["Total"=>$request->input("montoTotal")]);
            $request->merge(["venta_id"=>$temporal]);
            $request->merge(["cliente_id"=>$request->input("customer_id")]);
           
            $inputheadInvoiceRepo=new HeadInvoiceManager($headInvoice,
            $request->only('numero','cliente','direccion','ruc','GRemicion','subTotal',
                'igv','Total','venta_id','cliente_id','tipoDoc','vuelto'));
            $inputheadInvoiceRepo->save();
            $codigoFactura=$headInvoice->id;
            
     }
        //----------------

        $detOrderrepox;
        $HeadStockRepo;
         $codigoHeadIS=0;
       foreach($var as $object){
        $object['sale_id'] = $temporal;

           

           $detOrderrepox = new DetSaleRepo;

           $insertar=new DetSaleManager($detOrderrepox->getModel(),$object);
           $insertar->save();
          
           $detOrderrepox = null;

           //-------------------------------------
           
           $stockmodel = new StockRepo;
                  $object['warehouse_id']=$object['idAlmacen'];
                  $object["variant_id"]=$object['vari'];
                  $stockac=$stockmodel->encontrar($object["variant_id"],$object['warehouse_id']);
                  //var_dump($stockac);die();
            //--------------reporte stock------------
          if($codigoHeadIS===0){
            $object["warehouses_id"]=$object['idAlmacen'];
            //$object["cantidad_llegado"]=$cantidaCalculada;
            //$object['descripcion']='Entrada por compra';
            $object['tipo']='Venta';
            $object["user_id"]=auth()->user()->id;
            $object["Fecha"]=$request->input("fechaPedido");

            $HeadStockRepo = new HeadInputStockRepo;
            $HeadStock=$HeadStockRepo->getModel();
            $HeadStockinsert=new HeadInputStockManager($HeadStock,$object);
            $HeadStockinsert->save();
            $codigoHeadIS=$HeadStock->id;
          }

          $object['headInputStock_id']=$codigoHeadIS;
          $object["producto"]=$object['NombreProducto'];
          $object["cantidad_llegado"]=$object['cantidad'];
          $object['descripcion']='Salida por Venta';
          
          $inputRepo;
          $inputRepo = new InputStockRepo;
            $inputstock=$inputRepo->getModel();
            $inputInsert=new InputStockManager($inputstock,$object);
            $inputInsert->save();
          //---------------------------------------


            if(!empty($stockac)){
             
                if($object["equivalencia"]==null){
                  $object["stockActual"]=$stockac->stockActual-($object["cantidad"]);//
                  
                }else{
                  $object["stockActual"]=$stockac->stockActual-($object["cantidad"]*$object["equivalencia"]);
                  
                }
                  $manager = new StockManager($stockac,$object);
                  $manager->save();
                  //$stock=null;
            }else{
                
            }
            $stockac=null;
            //-----------------------------------------------------
            //Create det Documento Venta 
            //-------------------------------------------------------
            
            if(!empty($codigoFactura)){
                  $object["descripcion"]=$object["NombreProducto"];
                  $object["PrecioUnit"]=$object["precioVenta"];
                  $object["PrecioVent"]=$object["subTotal"];
                  $object["headInvoice_id"]=$codigoFactura;
                  $detInvoice=new DetailInvoiceRepo;
                  $insertDetInvoice=new DetailInvoiceManager($detInvoice->getModel(),$object);
                  $insertDetInvoice->save();
          }
             //var_dump($codigoFactura.'¿'.$vuelto);die();
         
            //------------------------------------------------------
       }
     if(!empty($request->input("comprobante"))){
      if($request->input("tipoDoc")=='TF' || $request->input("tipoDoc")=='TB' ){
        $this->generate_factura($codigoFactura,$vuelto,$cajaId,$cajaAct["id"],$request->input("tipoDoc"),$request->input('descuento'));
      }//else{
        //$this->generate_Boleta($codigoFactura,$vuelto,$cajaId,$request->input("tipoDoc"));
     // }
   }
       //-----------------Creacion de Cabecera Factura-------
       //$cajaPrueba=$request->saledetPayments;
       \DB::commit();
       if(!empty($codigoFactura)){
                return response()->json(['estado'=>true,'codFactura'=>$codigoFactura,'nombres'=>$orderSale->nombres]);
        }else{
           return response()->json(['estado'=>true,'nombres'=>$orderSale->nombres]);
        }      
    }
    Public function codTicket($tipo,$cajid){
            $numTicket='';
            $fbnumber=new FBnumberRepo;
            //var_dump($tipo.'-->'.$cajid);die();
            $numeros=$fbnumber->numeracionTiket($tipo,$cajid);
            if($numeros->numTiketFactura<10){
              return $numTicket.='00000'.$numeros->numTiketFactura;
            }
            if($numeros->numTiketFactura<100){
              return $numTicket.='0000'.$numeros->numTiketFactura;
            }
            if($numeros->numTiketFactura<1000){
              return $numTicket.='000'.$numeros->numTiketFactura;
            }
            if($numeros->numTiketFactura<10000){
              return $numTicket.='00'.$numeros->numTiketFactura;
            }
            if($numeros->numTiketFactura<100000){
              return $numTicket.='0'.$numeros->numTiketFactura;
            }else{
              return $numTicket.=''.$numeros->numTiketFactura;
            }
            
    }
     public function editPromocion(Request $request)
    {
        $promocion = $this->promocionRepo->find($request->id);
        //var_dump($material);
        //die(); 
        $manager = new PromocionManager($promocion,$request->except("fecha_inicio","fecha_fin"));
        $manager->save();

        //Event::fire('update.material',$material->all());
        return response()->json(['estado'=>true]);
    }
    public function concat(Request $request){
        //var_dump($request["idFactura"]);die();
        $this->generate_factura($request["idFactura"],$request["vuelto"],$request["caja"],
                        $request["cajadiari"],$request["tipo"],$request["descuento"]);
        return response()->json(['estado'=> true]);
    }
    public function generate_factura($idFactura,$vuelto,$caja,$cajadiari,$tipo,$descuento){

      $headInvoiceRepo=new HeadInvoiceRepo;
      $detailInvoiceRepo=new DetailInvoiceRepo;      
        $cabeceraFactura=$headInvoiceRepo->consult($idFactura);
        $detalleFactura=$detailInvoiceRepo->detFactura($idFactura);
        $tipoPago="E/T";
             if($cabeceraFactura->efectivo>0){
                 $tipoPago="Efec.";
             }else{
                  $tipoPago="Tarj.";
             }
         $txt = '<?php require_once(dirname(__FILE__) . "/escpos-php-master/Escpos.php");
              //$logo = new EscposImage("images/productos/tostao.jpg");

              $printer = new Escpos();
              /* Print top logo */
                            $printer -> setJustification(Escpos::JUSTIFY_CENTER);
                            ';
       // if($oCash->estado == 0)
        //{
                    $txt .= '
                             $printer ->  setEmphasis(true);
                             $printer -> text("FACTURA \n");
                             $printer -> text("'.$cabeceraFactura->razonSocial.' \n");
                             $printer -> text("C'.$cabeceraFactura->direccionEmpresa.' '.$cabeceraFactura->provincia.'-'.$cabeceraFactura->departamento.' \n");
                             $printer -> text("ruc:'.$cabeceraFactura->ruc.' \n");
                             $printer -> text("TICKET \n");
                             $printer -> text("00'.$caja.'-'.$this->codTicket($tipo,$caja).'\n");
                             $printer -> setEmphasis(false);
                             $printer -> feed();
                             $printer -> setJustification();'; 
               //   }elseif($oCash->estado == 1)
        //{
        //    $txt .= '$printer -> text("RESUMEN PARCIAL\nDE CAJA");';
        //}
        $txt .= '
              $printer -> setFont(Escpos::FONT_C);
              $printer -> feed();
              $printer -> text("#CAJA:'.$cabeceraFactura->cajaid.'       '.date("d-m-Y").' '.date("H:i:s").'\n");
              $printer -> text("Ticket                  <original>\n");
              $printer -> text("-------------------------------------\n");';
              if($tipo=="TF"){
                    $txt .= '$printer -> text("TIPO:'.$tipoPago.' RUC N°:'.$cabeceraFactura->ruc.'\n");';
              }else{
                    $txt .= '$printer -> text("TIPO:'.$tipoPago.' DNI N°:'.$cabeceraFactura->dni.'\n");';                     
              }

              
             $txt .= '
              $printer -> text("Cliente: '.$cabeceraFactura->cliente.'\n");
              $printer -> feed();
              $printer -> text("Direccion: '.$cabeceraFactura->direccion.'\n");
              $printer -> feed();
              $printer -> text("Cajero: '.auth()->user()->name.'\n");
              $printer -> text("Vendedor: '.$cabeceraFactura->nomEmpleado.'\n");
              $printer -> text("-------------------------------------\n");
              $printer -> text("Descripcion \n");
              $printer -> text("Precio      cant           Total \n");
              $printer -> text("-------------------------------------\n");
              ';
              foreach($detalleFactura as $detalleFactura){
                      $txt .='$printer -> text("'.$detalleFactura["descripcion"].'\n");
                              
                              $printer -> text("'.
                                                $detalleFactura["PrecioUnit"].'       '.$detalleFactura["cantidad"].
                                                '          '.$detalleFactura["PrecioVent"].'\n");
                              ';
                            }
                              $txt.='$printer -> text("-------------------------------------\n");
                              $printer -> text("IGV(18%)               S/.'.$cabeceraFactura->igv.'\n");                            
                              $printer -> text("Subtotal               S/.'.$cabeceraFactura->subTotal.'\n");
                              
                              
                              $printer -> text("Pago adelantado(anticipo)    0.00\n");
                              $printer -> text("Vale de Consumo              0.00\n");
                              $printer -> text("descuento especial         S/.'.$descuento.'\n");
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("Monto P. Tarjeta     S/.'.$cabeceraFactura->tarjeta.'\n"); 
                              $printer -> text("Monto P. Efectivo    S/.'.$cabeceraFactura->efectivo.'\n"); 
                              $printer -> text("TOTAL                S/.'.$cabeceraFactura->Total.'\n");
                              $printer -> feed(); 
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("Importe Pagado             S/.'.($cabeceraFactura->Total+$vuelto).'\n");
                              $printer -> text("Vuelto                 S/.'.$vuelto.'\n"); 
                              $printer -> text("-------------------------------------\n"); 
                              $printer -> text("-------------------------------------\n"); 
                              ';
              
              
             $txt.='$printer -> setEmphasis(true);';
              
        $txt .= '$printer -> text("-------------------------------------\n");';
        $txt .= '$printer -> setEmphasis(true);';
        $txt .= '$printer -> text("Comuniquense con nosotros al:\n");';
        $txt .= '$printer -> text("'.$cabeceraFactura->email.'\n");';
        $txt .= '$printer -> setEmphasis(false);';
        $txt .= '$printer -> feed();';
       
        
        $txt .= '$printer -> feed();';
        $txt .= '$printer -> cut();$printer -> pulse();';

        $txt .= '$printer -> close();';
        
        $myfile = fopen("../resources/ticket.php", "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
        $cmd = 'php '.base_path("/resources/").'ticket.php  > '.base_path("resources/").'ticket.txt';
        //$cmd = 'lpr -P Photosmart-Plus-B209a-m /var/www/html/4Rest/public/newfile.php';
        shell_exec($cmd);//exec('sudo -u myuser ls /');
        $cmd2 = 'lpr -P impresora_prueba -o raw '.base_path("resources/").'ticket.txt';
        shell_exec($cmd2);
        return response()->json('estado');
    }
   
    public function createSeparateSale(Request $request) 
        {
          \DB::beginTransaction();
          //---------------------- 
          $orderRepo;
            $orderRepo = new SeparateSaleRepo;
            $cajaSave=$orderRepo->getModel();
            $cash1 = $orderRepo->find($request->id);

            $manager1 = new SeparateSaleManager($cash1,$request->all());
            $manager1->save();
        //---------------------
        $var = $request->detOrders;
        $request->merge(array('estado' => '0'));
        //$request->merge(array('estado' => '0'));
        $orderSale = $this->saleRepo->getModel();
        $manager = new SaleManager($orderSale,$request->all());
        $manager->save();
 
        $orderSale->save();
        $temporal=$orderSale->id;
        
          //----------------------      

        $detOrderrepox;
        $montoventa=0;
         $HeadStockRepo;
         $codigoHeadIS=0;
       foreach($var as $object){
          //------Actualizar pedido------
          //$cajaAct = $request->caja;
          //var_dump($object);die();
            $saleDet;
            $saleDet = new DetSeparateSaleRepo;
            //$object=$saleDet->getModel();
            
            $saled = $saleDet->find($object['id'] );
            //var_dump($saled);die();

            $manager2 = new DetSeparateSaleManager($saled,$object);
            $manager2->save();
          //-----------------------------
           $object['sale_id'] = $temporal;
           $object['cantidad'] = $object['parteEntregado'];
           $object['subTotal'] = $object['precioVenta']*$object['parteEntregado'];
           $montoventa=$montoventa+$object['subTotal'];
           $detOrderrepox = new DetSaleRepo;

           $insertar=new DetSaleManager($detOrderrepox->getModel(),$object);
           if($object['parteEntregado']>0){$insertar->save();}
           
          
           $detOrderrepox = null;

           //-------------------------------------
           
           $stockmodel = new StockRepo;
                  $object['warehouse_id']=$object['idAlmacen'];
                  $object["variant_id"]=$object['vari'];
                  $stockac=$stockmodel->encontrar($object["variant_id"],$object['warehouse_id']);
                  //var_dump($stockac);die();

                  //--------------reporte stock------------
          if($codigoHeadIS===0){
            $object["warehouses_id"]=$object['idAlmacen'];
            //$object["cantidad_llegado"]=$cantidaCalculada;
            //$object['descripcion']='Entrada por compra';
            $object['tipo']='Venta';
            $object["user_id"]=auth()->user()->id;
            $object["Fecha"]=$request->input("fechaPedido");

            $HeadStockRepo = new HeadInputStockRepo;
            $HeadStock=$HeadStockRepo->getModel();
            $HeadStockinsert=new HeadInputStockManager($HeadStock,$object);
            $HeadStockinsert->save();
            $codigoHeadIS=$HeadStock->id;
          }

          $object['headInputStock_id']=$codigoHeadIS;
          $object["producto"]=$object['nameProducto']."(".$object['NombreAtributos'].")";
          $object["cantidad_llegado"]=$object['cantidad'];
          $object['descripcion']='Salida por Venta';
          
          $inputRepo;
          $inputRepo = new InputStockRepo;
            $inputstock=$inputRepo->getModel();
            $inputInsert=new InputStockManager($inputstock,$object);
            $inputInsert->save();
          //---------------------------------------

            if(!empty($stockac)){
             
                if($object["equivalencia"]==null){
                  $object["stockSeparados"]=$stockac->stockSeparados-($object["cantidad"]);
                  $object["stockActual"]=$stockac->stockActual-($object["cantidad"]);//
                  
                }else{
                  $object["stockActual"]=$stockac->stockActual-($object["cantidad"]*$object["equivalencia"]);
                  $object["stockSeparados"]=$stockac->stockSeparados-($object["cantidad"]*$object["equivalencia"]);
                }
                  $manager = new StockManager($stockac,$object);
                  $manager->save();
                  //$stock=null;
            }else{
                
            }
            $stockac=null;
            //-----------------------------------------------------
       }
            $subTotal=$montoventa/1.18;
            $montoIgv=$montoventa-$subTotal;

            $request->merge(array('montoTotal' => $montoventa));
            $request->merge(array('igv' => $montoIgv));
            $request->merge(array('montoBruto' => $subTotal));

            $sales=$this->saleRepo->find($temporal);
            $manager = new SaleManager($sales,$request->all());
            $manager->save();
            \DB::commit();
     return response()->json(['estado'=>true, 'nombres'=>$orderSale->nombres]);
    }

    public function createSale(Request $request) 
        {
          \DB::beginTransaction();
          //---------------------- 
          $orderRepo;
            $orderRepo = new OrderSaleRepo;
            $cajaSave=$orderRepo->getModel();
            $cash1 = $orderRepo->find($request->id);

            $manager1 = new OrderSaleManager($cash1,$request->all());
            $manager1->save();
        //---------------------
        $var = $request->detOrders;
        $request->merge(array('estado' => '0'));
        //$request->merge(array('estado' => '0'));
        $orderSale = $this->saleRepo->getModel();
        $manager = new SaleManager($orderSale,$request->all());
        $manager->save();
 
        $orderSale->save();
        $temporal=$orderSale->id;
        
          //----------------------      

        $detOrderrepox;
        $montoventa=0;

         $HeadStockRepo;
         $codigoHeadIS=0;
        
       foreach($var as $object){
          
          //------Actualizar pedido------
          //$cajaAct = $request->caja;
          //var_dump($object);die();
            $saleDet;
            $saleDet = new DetOrderSaleRepo;
            //$object=$saleDet->getModel();
            
            $saled = $saleDet->find($object['id'] );
            //var_dump($saled);die();

            $manager2 = new DetOrderSaleManager($saled,$object);
            $manager2->save();
          //-----------------------------
           $object['sale_id'] = $temporal;
           $object['cantidad'] = $object['parteEntregado'];
           $object['subTotal'] = $object['precioVenta']*$object['parteEntregado'];
           $montoventa=$montoventa+$object['subTotal'];
           $detOrderrepox = new DetSaleRepo;

           $insertar=new DetSaleManager($detOrderrepox->getModel(),$object);
           if($object['parteEntregado']>0){$insertar->save();}
           
          
           $detOrderrepox = null;

           //-------------------------------------
           
           $stockmodel = new StockRepo;
                  $object['warehouse_id']=$object['idAlmacen'];
                  $object["variant_id"]=$object['vari'];
                  $stockac=$stockmodel->encontrar($object["variant_id"],$object['warehouse_id']);
                  //var_dump($stockac);die();

                  //--------------reporte stock------------
          if($codigoHeadIS===0){
            $object["warehouses_id"]=$object['idAlmacen'];
            //$object["cantidad_llegado"]=$cantidaCalculada;
            //$object['descripcion']='Entrada por compra';
            $object['tipo']='Venta';
            $object["user_id"]=auth()->user()->id;
            $object["Fecha"]=$request->input("fechaPedido");

            $HeadStockRepo = new HeadInputStockRepo;
            $HeadStock=$HeadStockRepo->getModel();
            $HeadStockinsert=new HeadInputStockManager($HeadStock,$object);
            $HeadStockinsert->save();
            $codigoHeadIS=$HeadStock->id;
          }

          $object['headInputStock_id']=$codigoHeadIS;
          $object["producto"]=$object['nameProducto']."(".$object['NombreAtributos'].")";
          $object["cantidad_llegado"]=$object['cantidad'];
          $object['descripcion']='Salida por Venta';
          
          $inputRepo;
          $inputRepo = new InputStockRepo;
            $inputstock=$inputRepo->getModel();
            $inputInsert=new InputStockManager($inputstock,$object);
            $inputInsert->save();
          //---------------------------------------

            if(!empty($stockac)){
             
                if($object["equivalencia"]==null){
                  $object["stockPedidos"]=$stockac->stockPedidos-($object["cantidad"]);
                  $object["stockActual"]=$stockac->stockActual-($object["cantidad"]);//
                  
                }else{
                  $object["stockActual"]=$stockac->stockActual-($object["cantidad"]*$object["equivalencia"]);
                  $object["stockPedidos"]=$stockac->stockPedidos-($object["cantidad"]*$object["equivalencia"]);
                }
                  $manager = new StockManager($stockac,$object);
                  $manager->save();
                  //$stock=null;
            }else{
                
            }
            $stockac=null;
            //-----------------------------------------------------
       }
            $subTotal=$montoventa/1.18;
            $montoIgv=$montoventa-$subTotal;

            $request->merge(array('montoTotal' => $montoventa));
            $request->merge(array('igv' => $montoIgv));
            $request->merge(array('montoBruto' => $subTotal));

            $sales=$this->saleRepo->find($temporal);
            $manager = new SaleManager($sales,$request->all());
            $manager->save();
            \DB::commit();
     return response()->json(['estado'=>true, 'nombres'=>$orderSale->nombres]);
    }
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
  

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    
    public function edit(Request $request)
    {
       \DB::beginTransaction();
        $varDetOrders = $request->detOrder;
        $varPayment = $request->payment;
        $movimiento = $request->movimiento;
        if ($movimiento['montoMovimientoEfectivo']>0) {
            //---create movimiento--- 
            //var_dump($request->movimiento);die();
            $detCashrepo;
            $movimiento['observacion']="temporal";
            $detCashrepo = new DetCashRepo;
            $movimientoSave=$detCashrepo->getModel();
        
            $insertarMovimiento=new DetCashManager($movimientoSave,$movimiento);
            $insertarMovimiento->save();
    //---Autualizar Caja---
            
            $cajaAct = $request->caja;
            $cashrepo;
            $cashrepo = new CashRepo;
            $cajaSave=$cashrepo->getModel();
            $cash1 = $cashrepo->find($cajaAct["id"]);

            $manager1 = new CashManager($cash1,$cajaAct);
            $manager1->save();
        //----------------

            $salePaymentRepo;
        $salePaymentRepo = new SalePaymentRepo;
        $payment = $salePaymentRepo->find($varPayment['id']);
        $manager = new SalePaymentManager($payment,$varPayment);
        $manager->save();

        }
        
        $HeadStockRepo;
         $codigoHeadIS=0;
         
        //$detOrderSaleRepo;
        foreach($varDetOrders as $object){
            //$detOrderSaleRepo = new DetSaleRepo;

            //$detorderSale = $detOrderSaleRepo->find($object['id']);
            //$manager = new DetSaleManager($detorderSale,$object);
            //$manager->save();

            $stokRepo;
            $stokRepo = new StockRepo;
            $cajaSave=$stokRepo->getModel();
            $stockOri = $stokRepo->find($object['id']);

            $stock = $stokRepo->find($object['idStock']);
            //+++if ($object['estad']==true) {
                $stock->stockActual= $stock->stockActual+$object['cantidad'];
            //+++}else{
                //+++$stock->stockPedidos= $stock->stockPedidos+$object['canPendiente'];
            //+++}

            $stock->save();

            //--------------reporte stock------------
            $object["variant_id"]=$object['vari'];
          if($codigoHeadIS===0){
            $object["warehouses_id"]=$object['idAlmacen'];
            //$object["cantidad_llegado"]=$cantidaCalculada;
            //$object['descripcion']='Entrada por compra';
            $object['tipo']='Entrada Venta';
            $object["user_id"]=auth()->user()->id;
            $object["Fecha"]=$request->input("fechaPedido");

            $HeadStockRepo = new HeadInputStockRepo;
            $HeadStock=$HeadStockRepo->getModel();
            $HeadStockinsert=new HeadInputStockManager($HeadStock,$object);
            $HeadStockinsert->save();
            $codigoHeadIS=$HeadStock->id;
          }

          $object['headInputStock_id']=$codigoHeadIS;
          $object["producto"]=$object['nameProducto']."(".$object['NombreAtributos'].")";
          $object["cantidad_llegado"]=$object['cantidad'];
          $object['descripcion']='Entrada Venta Anulada';
          
          $inputRepo;
          $inputRepo = new InputStockRepo;
            $inputstock=$inputRepo->getModel();
            $inputInsert=new InputStockManager($inputstock,$object);
            $inputInsert->save();
          //---------------------------------------
        }

        $orderSale = $this->saleRepo->find($request->id);
        $manager = new SaleManager($orderSale,$request->all());
        $manager->save();

        

         \DB::commit();

        return response()->json(['estado'=>true, 'nombre'=>$orderSale->nombre]);
    }
    public function createPromcion(Request $request){
        $promocion = $this->promocionRepo->getModel();
        $manager = new PromocionManager($promocion,$request->all());
        $manager->save();
        return response()->json(['estado'=>true]);
    }
    public function paginatePromtion(){
        $promocion = $this->promocionRepo->listarPromociones();
        return response()->json($promocion);
    }
     public function confirmarVariante($id,$fecha){
        $promocion = $this->promocionRepo->confirmarVariante($id,$fecha);
        return response()->json($promocion);
    }
     public function destroy(Request $request)
    {
        $promocion= $this->promocionRepo->find($request->id);
        $promocion->delete();
        //Event::fire('update.Ttype',$Ttype->all());
        return response()->json(['estado'=>true]);
    }
    public function factura($id){
           $headIvoiceRepo = new HeadInvoiceRepo;
           $orders = $headIvoiceRepo->consult($id);
        return response()->json($orders);
    }
    public function detfactura($id){
           $detailIvoiceRepo = new DetailInvoiceRepo;
           $orders = $detailIvoiceRepo->detFactura($id);
        return response()->json($orders);
    }
     public function numeracion($tipo,$id){
           $headIvoiceRepo = new FBnumberRepo;
           $orders = $headIvoiceRepo->numeracion($tipo,$id);
        return response()->json($orders);
    }
    public function reportCliente($fi,$ff){
            //var_dump($fi);
            //var_dump($ff);
            //die();
             //return $ff;
        
        $database = \Config::get('database.connections.mysql');
        $time=time();
        $output = public_path() . '/report/'.$time.'_reportecliente';
        
        $ext = "pdf";
        
        \JasperPHP::process(
            public_path() . '/report/reportecliente.jasper', 
            $output, 
            array($ext),
            //array(),
            //while($i<=3){};
            ['fechaInicio' => $fi,
            'FechaF' => $ff],//Parametros
              
            $database,
            false,
            false
        )->execute();
    
   
        return '/report/'.$time.'_reportecliente.'.$ext;
    }
}
