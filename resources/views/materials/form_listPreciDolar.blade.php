<setion class="content-header"><h1>
            Lista Precio Dolar Sunat
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
          <a href="/materials/preDolar" type="submit" class="btn btn-primary pull-left">Registrar Precios</a> 
          </div>
        </div><br>
        <div class="row">
        <div class="col-md-12">

          <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Lista por meses</h3>
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
              <div class="col-md-10">
               <table class="table table-bordered">
                 <thead>
                   <tr>
                     <th>#</th>
                     <th style="text-align: center;">Fecha</th>
                     <th style="text-align: right;">Edit</th>
                   </tr>
                 </thead>
                 <tbody>
                   <tr ng-repeat="row in mesActuales">
                     <th>@{{$index+1}}</th>
                     <th style="text-align: center;">@{{row.mes}}</th>

                     <th style="text-align: right;"><input type="button" class="btn btn-warning" ng-click="editPReDolar(row)" value="Edit"></th>
                   </tr>
                 </tbody>
               </table>
              </div>
          </div>
               <div class="box-footer clearfix">
                  <pagination total-items="totalItems" ng-model="currentPage" max-size="maxSize" class="pagination-sm no-margin pull-right" items-per-page="itemsperPage" boundary-links="true" rotate="false" num-pages="numPages" ng-change="pageChanged()"></pagination>
               </div> 
                 
                
                </div><!-- /.box-body -->

                  <div class="box-footer">
                    
                  </div>
                </form>
              </div><!-- /.box -->

              </div>
              </div><!-- /.row -->
              </section><!-- /.content -->