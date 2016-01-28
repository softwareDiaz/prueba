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
                          <spam>@{{'   '+service.fechaServicio}}</spam>
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
                                <h3 class="box-title">Agregar Diagnostico</h3>
                              </div><!-- /.box-header -->

                              <div class="form-group" >
                                <label for="diagnostico">Diagnostico</label>
                                <textarea type="diagnostico" class="form-control" name="diagnostico" placeholder="Diagnostico"
                                          ng-model="service.diagnostico" rows="4" cols="50"></textarea>
                              </div>

                              <div class="form-group" >
                                <label for="accionCorrectiva">Accion Correctiva</label>
                                <textarea type="accionCorrectiva" class="form-control" name="accionCorrectiva" placeholder="Accion Correctiva"
                                          ng-model="service.accionCorrectiva" rows="4" cols="50"></textarea>
                              </div>

                            <div class="box box-primary">
                              <div class="box-header with-border">
                              <h3 class="box-title">Agregar Productos y servicios</h3>
                            </div><!-- /.box-header -->

                            <div class="row">
                              <div class="col-md-6">
                              <div class="form-group" >
                                  <label for="year">Tienda</label>
                                  <select class="form-control" name="" ng-model="store.id" ng-options="item.id as item.nombreTienda for item in stores">
                                    <option value="">--Elije Tienda--</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-6">
                              <div class="form-group" >
                                  <label for="year">Almacen</label>
                                  <select class="form-control" name="" ng-model="warehouse.id" ng-options="item.id as item.nombre for item in warehouses">
                                    <option value="">--Elije Almacen--</option>
                                  </select>
                                </div>
                              </div>
                            </div>

                            </br>

                              <div class="row">
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
                                <table class="table table-bordered">       
                                    <tr>
                                      <td>
                                          <button data-toggle="popover" popover-template="dynamicPopover.templateUrl" type="button" class="btn btn-default">@{{compras[$index].cantidad}}</button>
                                      </td>
                                      <td><a popover-template="dynamicPopover5.templateUrl" popover-trigger="mouseenter">@{{compras[$index].NombreAtributos}}</a></td>
                                      <td>
                                          <button data-toggle="popover" popover-template="dynamicPopover1.templateUrl" type="button" class="btn btn-default">@{{compras[$index].precioVenta| number:2}}</button>
                                      </td>
                                      <td>@{{compras[$index].subTotal | number:2}}</td>
                                      <td><button type="button" class="btn btn-danger ng-binding"  ng-click="sacarRow($index,row.subTotal)">
                                      <span class="glyphicon glyphicon-trash"></span>
                                      </td>                    
                                    </tr>                                    
                                  </table> 

                                <table class="table table-bordered">
                                  <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Precio Referencia</th>
                                    <th>Descuento</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Pedido</th>
                                    <th>Separados</th>
                                    <th>Precio Referencia</th>
                                    <th style="width: 40px">Eliminar</th>
                                  </tr>
                    
                                  <tr ng-repeat="row in services">
                                    <td>@{{$index + 1}}</td>
                                    <td>@{{row.numeroServicio}}</td>
                                    <td>@{{row.cliente}}</td>
                                    <td>@{{row.modelo}}</td>
                                    <td>@{{row.serie}}</td>
                                    <td>@{{row.telefono}}</td>
                                    <td>@{{row.tipo==0? 'Servicio':'Garantia'}}</td>
                                    <td>@{{row.estado==1? 'Por Revision': row.estado==2? 'Con Revision':'Otro'}}</td>
                                    <td><a ng-click="editService(row)" class="btn btn-warning btn-xs">Editar</a></td>
                                    <td><a ng-click="editServiceDiagnostico(row)" class="btn btn-warning btn-xs">Diagnostico</a></td>
                                    <td><a ng-click="deleteService(row)" class="btn btn-danger btn-xs">Eliminar</a></td>
                                  </tr>              
                                </table>

                </div><!-- /.box-body -->

                  <div class="box-footer">
                    <a type="submit" class="btn btn-primary" ng-click="update1Service()">Modificar</a>
                    <a href="/services" class="btn btn-danger">Cancelar</a>
                  </div>
                </form>
              </div><!-- /.box -->

              </div>
              </div><!-- /.row -->
              </section><!-- /.content -->


