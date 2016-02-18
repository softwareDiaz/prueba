<section class="content-header">
          <h1>
            Adelantos Proveedores
            <small>Panel de Control</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="/orderPurchases"><i class="fa fa-dashboard"></i>Orden de Compras</a></li>
          </ol>

          
        </section>

        <section class="content">
        <div class="row">
        <div class="col-md-12">

          <div class="box box-primary">
                
                <form name="paymentCreateForm" role="form" novalidate>
                  
      <div class="box-body">
<div class="row">

     <div ng-enabled="payment.Saldo>0" class="col-md-6" align="center">
     <div class="row">
           <div class="col-md-12">
                 <div  class="form-group" >
                      <b>Agrega Pago</b>
                 </div>
                  <div class="box-body table-responsive no-padding">
                 <table class="table table-striped" >
                    <tr>
                      <th style="width: 100px">Fecha</th>
                      <th style="width: 200px">Metodo de Pago</th>
                      <th style="width: 150px" ng-disabled="detPayment.methodPayment_id>0 || detPayment.cashe_id>0 || payment.cajamensual==true">Monto Pagado</th>
                    </tr>
                <tr >
                <td >

                               <div  class="input-group">
                                        <div class="input-group-addon">
                                              <i class="fa fa-calendar"></i>
                                        </div>
                                      <input  type="date"   class="form-control" name="fecha" ng-model="detPayment.fecha" required>
                                   </div>   
                                
                     
              </td>
              <td >
               <div class="form-group" ng-class="{true: 'has-error'}[ paymentCreateForm.warehouse.$error.required && paymentCreateForm.$submitted || paymentCreateForm.warehouse.$dirty && paymentCreateForm.warehouse.$invalid]">
                       
                       <select  ng-hide="show" class="form-control" name="warehouse" ng-change="desseleccionarMethodP()" ng-model="detPayment.saleMethodPayment_id" ng-options="item.id as item.nombre for item in saleMethodPayments" >
                       <option value="">--Elija Modo de Pago--</option>
                       </select>
                       <label ng-show="paymentCreateForm.$submitted || paymentCreateForm.warehouse.$dirty && paymentCreateForm.warehouse.$invalid">
                                <span ng-show="paymentCreateForm.warehouse.$invalid"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                </label>
                </div>
                 
              </td>
              <td>
                     <div class="form-group" ng-class="{true: 'has-error'}[ paymentCreateForm.montoPagando.$error.required && paymentCreateForm.$submitted || paymentCreateForm.montoPagando.$dirty && paymentCreateForm.montoPagando.$invalid]" >
                       <input ng-disabled="detPayment.cashe_id==undefined || detPayment.saleMethodPayment_id==undefined || detPayment.fecha==undefined" type="number" class="form-control" ng-model='detPayment.monto' ng-blur='recalPayments()' name="montoPagando" placeholder="0.00"  min='0' step="0.1" required>
                      
                     </div>

              </td>
              </tr>
            </table>
            </div>
          </div>
        </div>
        <div class="row">
             <div ng-hide="payment.cajamensual" class="col-md-3">
                <div class="form-group" ng-class="{true: 'has-error'}[ paymentCreateForm.warehouse.$error.required && paymentCreateForm.$submitted || paymentCreateForm.warehouse.$dirty && paymentCreateForm.warehouse.$invalid]">
                       <label>Pagar Con Caja</label>
                       <select  ng-disabled="detPayment.methodPayment_id>0 || check==true || payment.cajamensual==true" ng-change="TraerSales(detPayment.cashe_id)" class="form-control" name="warehouse"  ng-model="detPayment.cashe_id" ng-options="item.cashID as item.nombre for item in cashHeaders" >
                       <option value="">--Elija Caja--</option>
                       </select>
                       <label ng-show="paymentCreateForm.$submitted || paymentCreateForm.warehouse.$dirty && paymentCreateForm.warehouse.$invalid">
                                <span ng-show="paymentCreateForm.warehouse.$invalid"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                </label>
                </div>
                <label>@{{cashes.montoUsable}}</label>
            </div>
            
          
          <div ng-show="detPayment.methodPayment_id==5"class="col-md-6">
                      <div   class="form-group" >                            
                            <select   class="form-control" name="numcuenta"  size="3" ng-model="detPayment.NumCuenta"  ng-change="validarCuenta2()" ng-options="item.NumCuenta as 'Cta. N°.-'+item.NumCuenta for item in counts" >
                            
                       </select>
                      </div>
          </div>
         <!-- <div ng-show="payment.cajamensual" class="col-md-5">
                   <em>Descripcion .</em>
                   <div class="form-group" >
                        <textarea ng-model="payment.descripcion" class="form-control input-lg">
                         </textarea>
                    </div>
        </div>-->
         
        </div>
      </div>
        

        <div class="col-md-6" align="center">
            <div class="form-group">
              <b>Pagos Realizados</b>
              </div>
            <div class="box-body table-responsive no-padding">
            <table class="table table-striped" >
                <thead style="display: block;">
                    <tr>
                      <th Style="width:26%;">Fecha</th>
                      <th>Tipo de Pago</th>
                      <th>Monto Pagado</th>
                      <th>Tipo Pago</th>
                      <th>Descartar</th>
                    </tr>
                 </thead>
                 <tbody style="display: block; width:auto; height: 200px; overflow-x: auto;">  
                    <tr ng-repeat="row in detPayments">
                      <td ng-hide="true">@{{row.id}}</td>
                      <td ng-hide="true">@{{row.cashID}}</td>
                      <td ng-hide="true">@{{row.Saldo_F}}</td>
                      <td>@{{row.fecha}}</td>
                      <td Style="width:26%;">caja Diaria</td>
                      <td Style="width:20%;">@{{row.monto}}</td>
                      <td Style="width:15%;" ng-if="row.tipoPago=='A'"><span class="badge bg-yellow">@{{row.tipoPago}}</span></td> 
                      <td Style="width:15%;" ng-if="row.tipoPago=='P'"><span class="badge bg-green">@{{row.tipoPago}}</span></td> 
                     <td><button type="button" class="btn btn-danger btn-xs"  ng-click="destroyPay(row)">
                        <span class="glyphicon glyphicon-trash"></span></button>
                     <a ng-show="false"ng-click="editDetpayment(row)" ng-model="checked" class="btn btn-warning btn-xs">Edit</a></td>
                    </tr>
                
                </tbody>
                   </table>
                   </div> 
            </div>
      </div>
            <div ng-hide="mostrarBtnGEd" class="form-group" >
                    <button ng-disabled="desactivarCuentas" class=" label-default" type='submit' ng-click='createPayment()' >Registrar Pago</button>  
                     <a ng-href="@{{pdf7}}" target="_blank" class="btn btn-primary btn-xs">@{{desscripctiondddd}}</a>
            
            </div> 
            <div ng-show="mostrarBtnGEd" class="form-group" >
                    <button class=" label-default" type='submit' ng-click='editPayment()'>Edit Pago</button>  
                    <button class=" label-danger"  ng-click='cancelarEditPayment()'>Cancelar</button>  
            
            </div>
        </div>
     
             
                   <div class="box-footer">
                    <a href="/services" class="btn btn-danger">Salir</a>
                  </div>
                </form>
              </div><!-- /.box -->

              </div>
              </div><!-- /.row -->
              </section><!-- /.content -->