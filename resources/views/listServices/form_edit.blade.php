<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Servicios
            <small>Panel de Control</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="/listServices">Servicios</li>
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
                <form name="listServiceCreateForm" role="form" novalidate>
                  <div class="box-body">
                  <div class="callout callout-danger" ng-show="errors">
                                                  <ul>
                                              <li ng-repeat="row in errors track by $index"><strong >@{{row}}</strong></li>
                                              </ul>
                                            </div>
                   <div class="row">
                   <div  class="col-md-1"></div>
                   <div  class="col-md-6">
                    <div class="form-group" ng-class="{true: 'has-error'}[ listServiceCreateForm.nombre.$error.required && listServiceCreateForm.$submitted || listServiceCreateForm.nombre.$dirty && listServiceCreateForm.nombre.$invalid]">
                      <label for="nombre">Nombre</label>
                      <input type="text" class="form-control" name="nombre" placeholder="Nombre" ng-model="listService.nomServicio" required>
                      <label ng-show="listServiceCreateForm.$submitted || listServiceCreateForm.nombre.$dirty && listServiceCreateForm.nombre.$invalid">
                        <span ng-show="listServiceCreateForm.nombre.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                      </label>
                    </div>
                    </div>
                    <div  class="col-md-4">
                    <div class="form-group" >
                            <label for="tipo">Tipo documento</label>
                            <select class="form-control" ng-model="listService.tipo" >
                                    <option value="Garantia">Garantia</option>
                                    <option value="Normal">Normal</option>
                             </select>
                     </div>
                     </div>
                  </div>
                  <div class="row">
                   <div  class="col-md-1"></div>
                   <div  class="col-md-10">
                    <div class="form-group" >
                      <label for="descripcion">Descripcion</label>
                      <textarea type="descripcion" class="form-control" name="descripcion" placeholder="Descripcion"
                      ng-model="listService.descripcion" rows="4" cols="50"></textarea>
                     </div>
                    </div>
                  </div>
                  <div class="row">
                       <div  class="col-md-1"></div>
                       <div  class="col-md-4">
                              <div class="form-group" ng-class="{true: 'has-error'}[ listServiceCreateForm.cantidad.$error.required && listServiceCreateForm.$submitted || listServiceCreateForm.cantidad.$dirty && listServiceCreateForm.cantidad.$invalid]">
                                 <label for="cantidad">Costo Servicio</label>
                                 <input  type="number" class="form-control ng-pristine ng-valid ng-touched" name="cantidad" id="cantidad" placeholder="0.00" ng-model="listService.costoAprox" min="0" step="1" >
                                 <label ng-show="listServiceCreateForm.$submitted || listServiceCreateForm.cantidad.$dirty && listServiceCreateForm.cantidad.$invalid">
                                   <span ng-show="listServiceCreateForm.cantidad.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                                </label>
                             </div>
                       </div>
                       <div  class="col-md-4">
                                <div  class="form-group" >
                                     <br>                            
                                     <input  type="checkbox"  name="variantes" ng-model="listService.estado" />
                                     <span class="text-info"> <em>Selecione para activar estado!!</em></span>
                                </div>
                       </div>
                  </div>

                </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" ng-click="updatelistService()">Modificar</button>
                    <a href="/listServices" class="btn btn-danger">Cancelar</a>
                  </div>
                </form>
              </div><!-- /.box -->

              </div>
              </div><!-- /.row -->
              </section><!-- /.content -->