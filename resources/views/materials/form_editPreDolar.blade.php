<setion class="content-header"><h1>
            Precio Dolar Sunat
            <small>Panel de Control</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class=""><a href="/atributes">Marcas</a> </li>
            <li class="active">Editar</li>
          </ol>


        </section>

        <section class="content">
        <div class="row">
        <div class="col-md-12">

          <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Precio Dolar</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form name="brandCreateForm" role="form" novalidate>
                  <div class="box-body">
                  <div class="callout callout-danger" ng-show="errors">
                                                  <ul>
                                              <li ng-repeat="row in errors track by $index"><strong >@{{row}}</strong></li>
                                              </ul>
                                            </div>
          <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-4">
                  <select class="form-control" ng-change="llenardolar()" ng-model="mes" ng-disabled="true">
                     <option value="">--Seleccione Mes--</option>
                     <option value="1">Enero</option>
                     <option value="2">Febrero</option>
                     <option value="3">Marzo</option>
                     <option value="4">Abril</option>
                     <option value="5">Mayo</option>
                     <option value="6">Junio</option>
                     <option value="7">Julio</option>
                     <option value="8">Agosto</option>
                     <option value="9">Setiembre</option>
                     <option value="10">Octubre</option>
                     <option value="11">Noviembre</option>
                     <option value="12">Diciembre</option>
                 </select>
              </div>
          </div><br>
          <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-10">
               <table class="table table-bordered">
                 <thead>
                   <tr>
                     <th>#</th>
                     <th>Fecha</th>
                     <th>Precio Dolar</th>
                     <th>Edit</th>
                   </tr>
                 </thead>
                 <tbody>
                   <tr ng-repeat="row in mesActuales">
                     <th>@{{$index+1}}</th>
                     <th ng-hide="true">@{{row.id}}</th>
                     <th ng-hide="true">@{{row.fecha}}</th>
                     <th>@{{row.fecha2}}</th>
                     <th ng-hide="true">@{{row.mes}}</th>
                     <th><input style="width:300px;" string-to-number  type="number" class="form-control" ng-model="row.preDolar"></th>
                     <th><input type="button" class="btn btn-warning" ng-click="actualizarPredolar(row)" value="Modificar"></th>
                   </tr>
                 </tbody>
               </table>
              </div>
          </div>
               
                 
                
                </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" ng-click="cretatePredolar()">Crear</button>
                    <a href="/brands" class="btn btn-danger">Cancelar</a>
                  </div>
                </form>
              </div><!-- /.box -->

              </div>
              </div><!-- /.row -->
              </section><!-- /.content -->