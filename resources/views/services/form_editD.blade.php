<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Servicios
            <small>Panel de Control</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class=""><a href="/services">Servicios</a> </li>
            <li class="active">Editar</li>
          </ol>


        </section>

        <section class="content">
        <div class="row">
        <div class="col-md-12">

          <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Editar Servicios</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form name="serviceEditForm" role="form" novalidate>
                  <div class="box-body">
                  <div class="callout callout-danger" ng-show="errors">
                                                  <ul>
                                              <li ng-repeat="row in errors track by $index"><strong >@{{row}}</strong></li>
                                              </ul>
                                            </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div  class="input-group">
                          <label>Fecha: </label>
                          <spam>@{{'   '+service.fechaServicio2}}</spam>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div  class="input-group">
                          <label>N° Orden: </label>
                          <spam>@{{'   '+service.numeroServicio}}</spam>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div  class="input-group">
                          <label>Cliente: </label>
                          <spam>@{{'   '+service.cliente}}</spam>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div  class="input-group">
                          <label>Empresa : </label>
                          <spam>@{{'   '+service.empresa}}</spam>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div  class="input-group">
                          <label>Direccion: </label>
                          <spam>@{{'   '+service.direcion}}</spam>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div  class="input-group">
                          <label>RUC: </label>
                          <spam>@{{'   '+service.ruc}}</spam>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div  class="input-group">
                          <label>Telefono Referencia: </label>
                          <spam>@{{'   '+service.telefono}}</spam>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div  class="input-group">
                          <label>Tipo: </label>
                          <spam>@{{service.tipo==0? '   Servicio':'   Garantia'}}</spam>
                        </div>
                      </div>

                      <div class="col-md-3" ng-if="service.tipo==1">
                        <div  class="input-group">
                          <label>Orden de trabajo: </label>
                          <spam>@{{'   '+service.ordenTrabajo}}</spam>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div  class="input-group">
                          <label>Estado: </label>
                          <spam>@{{'   '+service.estado==1? 'Por Revision': service.estado==2? 'Con Revision':
                          service.estado==3? 'En Reparacion':service.estado==4? 'Por Entregar':'Entregado'}}</spam>
                        </div>
                      </div>
                    </div>

                  


                              <div class="box box-primary">
                                <div class="box-header with-border">
                                <h3 class="box-title">Datos Del Equipo</h3>
                              </div><!-- /.box-header -->


                              <div class="row">
                                <div class="col-md-12">
                                  <div  class="input-group">
                                    <label>Descripcion Impresora: </label>
                                    <spam>@{{'   '+service.descripcion}}</spam>
                                  </div>
                                </div>
                              </div>


                              <div class="row">
                                <div class="col-md-6">
                                  <div  class="input-group">
                                    <label>Modelo: </label>
                                    <spam>@{{'   '+service.modelo}}</spam>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div  class="input-group">
                                    <label>Serie: </label>
                                    <spam>@{{'   '+service.serie}}</spam>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-12">
                                  <div  class="input-group">
                                    <label>Accesorios: </label>
                                    <spam>@{{'   '+service.accesorios}}</spam>
                                  </div>
                                </div>
                              </div>

                              <div class="box box-primary">
                                <div class="box-header with-border">
                                <h3 class="box-title">Control Diagnostico</h3>
                              </div><!-- /.box-header -->

                              <div class="form-group">
                                <label for="diagnostico">Diagnostico</label>
                                <textarea ng-disabled="true" type="diagnostico" class="form-control" name="diagnostico" placeholder="Diagnostico"
                                          ng-model="service.diagnostico" rows="4" cols="50"></textarea>
                              </div>

                              <div class="form-group" >
                                <label for="accionCorrectiva">Accion Correctiva</label>
                                <textarea ng-disabled="estBan"  type="accionCorrectiva" class="form-control" name="accionCorrectiva" placeholder="Accion Correctiva"
                                          ng-model="service.accionCorrectiva" rows="4" cols="50"></textarea>
                              </div>
                              <div class="row" ng-if="!estBan">
                                <div class="col-md-6">
                                      <div class="form-group">
                                            <label>Estado del Servicio</label>
                                            <select name="estado" class="form-control ng-pristine ng-valid ng-touched" ng-model="service.estado">
                                             <option value="1">Por Revision</option>
                                             <option value="2">Con Revision</option>
                                             <option value="3">En Reparacion</option>
                                             <option value="4">Por Entregar</option>
                                             <option value="5">Entregar</option>

                                            </select>
                                      </div>
                                </div>
                              </div>
              <div ng-show="estBan1">
                            <div class="box box-primary">
                              <div class="box-header with-border">
                              <h3 class="box-title">Control Productos y servicios</h3>
                            </div><!-- /.box-header -->

                        <div ng-show="service.estado!=5">
                            <div class="row">
                              <div class="col-md-4">
                              <div class="form-group" >
                                  <label for="year">Tienda</label>
                                  <select class="form-control" name="" ng-model="store.id" ng-options="item.id as item.nombreTienda for item in stores">
                                    <option value="">--Elije Tienda--</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-4" ng-if="tipoServicio==1">
                              <div class="form-group" >
                                  <label for="year">Almacen</label>
                                  <select class="form-control" name="" ng-model="warehouse.id" ng-options="item.id as item.nombre for item in warehouses">
                                    <option value="">--Elije Almacen--</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-4">
                                      <div class="form-group">
                                            <label>Elija tipo de trabajo</label>
                                            <select name="genero" class="form-control ng-pristine ng-valid ng-touched" ng-model="tipoServicio">
                                             <option value="0">Servicio</option>
                                             <option value="1">Producto</option>

                                            </select>
                                      </div>
                              </div>
                            </div>

                            </br>

                              <div class="row" ng-show="tipoServicio==1">
                                  <div class="col-md-9" ng-show="skuestado">
                                    <input type="text" ng-model="varianteSkuSelected" placeholder="Buscar por SKU" ng-enter="getvariantSKU()" class="form-control">
                                  </div>

                                  <div class="col-md-9" ng-show="!skuestado">
                                    <input  type="text" ng-model="atributoSelected" ng-enter="open()" placeholder="Buscar por codigo" typeahead="atributo as atributo.NombreAtributos for atributo in getAtributos($viewValue)" 
                                    typeahead-loading="loadingLocations" typeahead-no-results="noResults" class="form-control"/>
                                  </div>
                                  <div class="col-md-3" >
                                  <div class="form-group">
                                      <input type="checkbox" name="estado" ng-model="base" ng-checked="base" class="ng-valid ng-dirty ng-valid-parse ng-touched" ng-click="baseestado()">
                                      <label for="estado">Base</label>                             
                              
                                      <input type="checkbox" name="skuestado" ng-model="skuestado" ng-checked="skuestado" class="ng-valid ng-dirty ng-valid-parse ng-touched">
                                      <label for="estado">SKU</label>                             
                                    </div>
                                  </div>
                                </div> 

                                <div class="row" ng-show="tipoServicio==0">
                                  <div class="col-md-9" ng-show="!skuestado">
                                    <input  type="text" ng-model="serviceSelected" ng-enter="cargarservice()" placeholder="Buscar por Nombre Servicio" typeahead="atributo as atributo.NombreAtributos for atributo in getService($viewValue)" 
                                    typeahead-loading="loadingLocations" typeahead-no-results="noResults" class="form-control"/>
                                  </div>
                                  <div class="col-md-3" >
                                  </div>
                                </div> 
                                <br>
                            </div>
                      </div>
                              <div class="box-body table-responsive no-padding">

                                <table class="table table-bordered">       
                                    <tr>
                                      <td>
                                          <button ng-if="compra.cantidad!=undefined" data-toggle="popover" popover-template="dynamicPopover.templateUrl" type="button" class="btn btn-default">@{{compra.cantidad}}</button>
                                      </td>
                                      <td><a popover-template="dynamicPopover5.templateUrl" popover-trigger="mouseenter">@{{compra.NombreAtributos}}</a></td>
                                      <td>
                                          <button ng-if="compra.cantidad!=undefined" data-toggle="popover" popover-template="dynamicPopover1.templateUrl" type="button" class="btn btn-default">@{{compra.precioVenta| number:2}}</button>
                                      </td>
                                      <td>@{{compra.subTotal | number:2}}</td>
                                      <td><a ng-if="compra.cantidad!=undefined" type="submit" class="btn btn-primary" ng-click="agregarTrabajo()">Add</a></td>
                                      <td><button ng-if="compra.cantidad!=undefined" type="button" class="btn btn-danger ng-binding"  ng-click="sacarRow($index,row.subTotal)">
                                      <span class="glyphicon glyphicon-trash"></span></button>

                                      
                                      </td>                    
                                    </tr>                                    
                                  </table> </div>
                                  <div class="box-body table-responsive no-padding">

                                <table class="table table-bordered">
                                  <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Precio Referencia</th>
                                    <th>Descuento</th>
                                    <th>Precio Venta</th>
                                    <th>Sub Total</th>
                                    <th style="width: 40px" ng-if="service.estado!=5">Eliminar</th>
                                  </tr>
                    
                                  <tr ng-repeat="row in detServices">
                                    <td>@{{$index + 1}}</td>
                                    <td>@{{row.NombreAtributos}}</td>
                                    <td>@{{row.cantidad}}</td>
                                    <td>@{{row.precioProducto}}</td>
                                    <td>@{{row.descuento}}</td>
                                    <td>@{{row.precioVenta}}</td>
                                    <td>@{{row.subTotal}}</td>
                                    <td ng-if="service.estado!=5"><a ng-click="destroyDetService(row)" class="btn btn-danger btn-xs">Eliminar</a></td>
                                  </tr>              
                                </table></div>

                              <a ng-if="(service.estado!=5 && service.tipo!=1)" ng-click="rutaMovimiento()" ng-href="@{{rutaSalesService}}"  target="_self" type="submit" class="btn btn-primary" >Pagar</a>

                </div><!-- /.box-body -->
        </div>  

                  <div class="box-footer">
                    <a ng-show="!estBan" type="submit" class="btn btn-primary" ng-click="update1Service()">Grabar</a>
                    <a ng-show="!estBan" href="/services" class="btn btn-danger">Cancelar</a>
                    <a ng-show="estBan" href="/services" class="btn btn-danger">Regresar</a>
                  </div>
                </form>
              </div><!-- /.box -->

              </div>
              </div><!-- /.row -->
              </section><!-- /.content -->






              <script type="text/ng-template" id="myPopoverTemplate5.html">
      <div class="form-group">
          <label>@{{dynamicPopover5.title}}</label>
        </div>

        <div>
        <label>Stock : </label>
        <label>@{{compra.Stock}}</label>
        </div>

        <div>
        <label>Pedidos : </label>
        <label>@{{compra.stockPedidos}}</label>
        </div>

        <div>
        <label>Separados : </label>
        <label>@{{compra.stockSeparados}}</label>
        </div>

        <div>
        <label>precio : </label>
        <label>@{{compra.precioProducto}}</label>
        </div>
          
                 
    </script>




     <script type="text/ng-template" id="myPopoverTemplate.html">
        <div class="form-group">
          <label>@{{dynamicPopover.title}}</label>
          <div class="row" >
          <div class="col-md-9">
            <input type="number" min="0" ng-model="compra.cantidad" ng-change="calcularmontos($index)" class="form-control">
            </div>
            <button type="button" class="btn btn-xs" ng-click="aumentarCantidad($index)">
            <span type="button" class="glyphicon glyphicon-plus"></span></button>
            <button type="button" class="btn btn-xs" ng-click="disminuirCantidad($index)">
            <span type="button" class="glyphicon glyphicon-minus"></span></button>

          </div>
        </div>
    </script>
    <hr />
    <script type="text/ng-template" id="myPopoverTemplate1.html">
        
      <tabset justified="true">

      <tab heading="Descuento">
        <label>@{{dynamicPopover1.title1}}</label>
            <div class="row" >
            <div class="col-md-9">
            <input type="number" ng-model="compra.descuento" ng-change="keyUpDescuento($index)"class="form-control">
          </div>
         <button type="button" class="btn btn-xs" ng-click="aumentarDescuento($index)">
          <span type="button" class="glyphicon glyphicon-plus"></span></button>
         <button type="button" class="btn btn-xs" ng-click="disminuirDescuento($index)">
         <span type="button" class="glyphicon glyphicon-minus"></span></button>

        </div>
        </div>

      </tab>
      <tab heading="Precio Unidad"><div class="form-group">
            <label>@{{dynamicPopover1.title}}</label>
            <div class="row" >
            <div class="col-md-9">
            <input type="number" min="0" ng-change="calcularmontos($index)" ng-model="compra.precioVenta" class="form-control">
          </div>
         <button type="button" class="btn btn-xs" ng-click="aumentarPrecio($index)">
          <span type="button" class="glyphicon glyphicon-plus"></span></button>
         <button type="button" class="btn btn-xs" ng-click="disminuirPrecio($index)">
         <span type="button" class="glyphicon glyphicon-minus"></span></button>

        </div>
        </div> 

        </tab>
      </tabset>
              
    </script>


    <script type="text/ng-template" id="myModalContent.html">
        <div class="modal-header">
            <h3 class="modal-title">Presentaciones</h3>
        </div><div class="box-body table-responsive no-padding">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Nombre</th>
                      <th>Precio</th>
                      <th>Presentacion</th>
                      <th>Equivalencia</th>
                      <th>Producto Base</th>

                      <th style="width: 40px">Enviar</th>
                    </tr>
                    
                    <tr ng-repeat="row in presentations">
                      <td>@{{$index + 1}}</td>
                      <td ng-hide="true">@{{row.iddetalleP}}</td>
                      <td >@{{row.NombreAtributos}}</td>
                      <td>@{{row.precioProducto}}</td> 
                      <td>@{{row.Presentacion}}</td>  
                      <td>@{{row.equivalencia}} @{{row.nomBase}}</td>
                      <td ng-if="row.base==0"><span class="badge bg-red">NO</span></td> 
                      <td ng-if="row.base!=0"><span class="badge bg-green">SI</span></td> 
                      <td><a ng-click="AsignarCompra(row)" class="btn btn-warning btn-xs" data-dismiss="modal">Enviar</a></td>

                    </tr>                                       
                  </table></div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">OK</button>
            <button class="btn btn-warning" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>


