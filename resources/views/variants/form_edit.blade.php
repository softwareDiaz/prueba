<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Variantes
        <small>Panel de Control</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="/variants">Variantes</li>
        <li class="active">Editar</li>
    </ol>


</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Editar Variante de <b>@{{ product.nombre }}</b></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form name="variantCreateForm" role="form" novalidate>
                    <div class="box-body">
                        <div class="callout callout-danger" ng-show="errors">
                            <ul>
                                <li ng-repeat="row in errors track by $index"><strong >@{{row}}</strong></li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{true: 'has-error'}[ variantCreateForm.nombre.$error.required && variantCreateForm.$submitted || variantCreateForm.nombre.$dirty && variantCreateForm.nombre.$invalid]">
                                    <label for="nombres">Código (Autogenerado)</label>
                                    <input type="text" class="form-control" name="codigo" placeholder="Codigo autogenerado" ng-model="variant.codigo" required>
                                    <label ng-show="variantCreateForm.$submitted || variantCreateForm.codigo.$dirty && variantCreateForm.codigo.$invalid">
                                        <span ng-show="variantCreateForm.codigo.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                                    </label>
                                </div></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Vencimiento</label>
                                    <input type="date" class="form-control" ng-model="variant.fvenc" placeholder="yyyy-MM-dd" />

                                </div>

                            </div>

                            </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Presentación Base:</label>
                                    <select  class="form-control" ng-model="variant.presentation_base_object" ng-change="changePreBase()" ng-options="item as item.nombre for item in presentations_base">
                                        <option value="">-- Elige Presentación Base--</option>
                                    </select>
                                </div>
                            </div>



                        </div>
                        <div class="row">



                        </div>


                        <div class="form-group">
                            <label for="notas">Notas</label>
                      <textarea type="notas" class="form-control" name="notas" placeholder="..."
                                ng-model="variant.nota" rows="4" cols="50"></textarea>
                        </div>
                            </div>
                            <div class="col-md-4">

                                    <div class="form-group">
                                        <label>Imagen</label>
                                        <input type="file" ng-model="variant.image" id="variantImage" name="variantImage"/>

                                    </div>
                                <div class="form-group">
                                    <img ng-src="@{{variant.image}}" alt="" class="img-thumbnail"/>
                                </div>

                            </div>
                        </div>
                        <!--  =============================================================================ATRIBUTOS===============================================================-->
                        <div class="box box-default" id="inventory">
                            <div class="box-header with-border">
                                <h3 class="box-title">Definir Atributos</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div><!-- /.box-tools -->
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <p class="text-light-blue"><button class="btn btn-xs btn-info btn-flat" ng-click="addAttribute()">Añadir Atributo</button></p>
                                <p class="text-light-blue"><em>Los Atributos permiten diferenciar las variantes de cada producto. Se puede agregar Atributos a su gusto.</em></p>

                                <!--<input type="search" ng-model="qA" placeholder="filtrar atributo..." aria-label="filtrar atributo" />-->
                                <div class="row" ng-repeat="row in attributes" ng-init="variant.detAtr[$index].atribute_id = row.id">
                                    <div class="col-md-2">
                                        <input type="hidden" ng-model="variant.detAtr[$index].atribute_id">
                                        <label for="">@{{ row.nombre }}</label>

                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" ng-model="variant.detAtr[$index].descripcion"  ng-init="asignarDescr($index)" ng-keyup="capAttr(row.id)" typeahead="state for state in opcAtr[row.id] | filter:$viewValue | limitTo:8">
                                    </div>
                                </div>



                            </div><!-- /.box-body -->

                        </div>

                        <!--  =============================================================================FIN ATRIBUTOS===============================================================-->

                        <!-- /.box -->
                        <!--  =============================================================================PRESENTACIONES===============================================================-->
                        <div class="box box-default" id="price">
                            <div class="box-header with-border">
                                <h3 class="box-title">Presentaciones del Producto     </h3>
                                <button class="btn btn-xs btn-info btn-flat" data-toggle="modal" data-target="#presentation" ng-click="traerPres(variant.presentation_base)" ng-disabled="enabled_presentation_button" >Añadir Presentación</button>
                                <button class="btn btn-xs btn-warning btn-flat" data-toggle="modal" data-target="#createpresentation"  ng-disabled="enabled_createpresentation_button" >Crear Presentación</button>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div><!-- /.box-tools -->
                            </div><!-- /.box-header -->
                            <div class="box-body">

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody><tr>
                                                <th>#</th>
                                                <th>Presentación</th>
                                                <th>Precio de Proveedor Soles</th>
                                                <th>Tipo de Cambio</th>
                                                <th>Precio de Proveedor Dólares</th>
                                                <th>% de Utilidad</th>
                                                <th>Cant. de Utilidad</th>
                                                <th>Precio de Venta</th>
                                                <th>% Descuento</th>
                                                <th>Cant de Descuento</th>
                                                <th>PVP</th>
                                                <th>Dscto Rango Activado</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>% Descuento</th>
                                                <th>Cant de Descuento</th>
                                                <th>PVP (Rango)</th>
                                                <th>Opciones</th>
                                            </tr>
                                            <tr ng-repeat="row in variant.presentations">
                                                <td>@{{$index + 1}}</td>
                                                <td>@{{row.nombre}}</td>
                                                <td>@{{row.suppPri}}</td>
                                                <td>@{{row.cambioDolar}}</td>
                                                <td>@{{row.suppPriDol}}</td>
                                                <td>@{{row.markup}}</td>
                                                <td>@{{row.markupCant}}</td>
                                                <td>@{{row.price}}</td>
                                                <td>@{{row.dscto}}</td>
                                                <td>@{{row.dsctoCant}}</td>
                                                <td>@{{row.pvp}}</td>
                                                <td ng-if="row.activateDsctoRange == '1'" style="color:red;">SI</td>
                                                <td ng-if="row.activateDsctoRange == '0'" style="color:red;">NO</td>
                                                <td>@{{row.fecIniDscto}}</td>
                                                <td>@{{row.fecFinDscto}}</td>
                                                <td>@{{row.dsctoRange}}</td>
                                                <td>@{{row.dsctoCantRange}}</td>
                                                <td>@{{row.pvpRange}}</td>

                                                <td>
                                                    <a href="" class="btn btn-warning btn-xs" ng-click="editPres(row, $index)"><i class="fa fa-fw fa-pencil"></i></a>
                                                    <a href="" class="btn btn-danger btn-xs" ng-click="deletePres($index)"><i class="fa fa-fw fa-trash"></i></a>
                                                </td>
                                            </tr>



                                            </tbody></table></div> <!--div responsive-->
                                    </div>


                                </div>
                            </div><!-- /.box-body -->


                        </div><!-- /.box -->

                        <!--  =============================================================================INVENTARIOS===============================================================-->
                        <div class="box box-default" id="inventory">
                            <div class="box-header with-border">
                                <h3 class="box-title">Inventario</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div><!-- /.box-tools -->
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="sku">Sku: <br>(Stock Keep Unit) </label>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input class="form-control" name="sku" type="text" ng-disabled="variant.autogenerado" ng-model="variant.sku" ng-disabled="variant.autogenerado" ng-required="!variant.autogenerado"/>
                                            <span style="color:#dd4b39;" ng-show="variantCreateForm.sku.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="checkbox" ng-model="variant.autogenerado"> Autogenerado
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <label for="variantes">¿Desea seguir el stock del Producto?</label>
                                    <input type="checkbox" name="trackeo" ng-model="variant.track" ng-checked="variant.track"/>

                                    <span class="text-info"> <em> </em></span>

                                    <div class="" ng-show="variant.track">

                                        <div class="box-tools">
                                            <div class="input-group" style="width: 150px;">
                                                <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search" ng-model="query">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                                                                                <span ng-repeat="row in warehouses | filter:query">
                                                                                                <div class="col-md-3 col-md-offset-1">
                                                                                                    <div class="form-group" >
                                                                                                        <label for=""></label>
                                                                                                        <h5>@{{ row.nombre }}</h5>
                                                                                                        <input type="hidden" class="" ng-model="variant.stock[$index].warehouse_id" ng-init="variant.stock[$index].warehouse_id = row.id"/>

                                                                                                    </div></div>

                                                                                                <div class="col-md-2">
                                                                                                    <div class="form-group" >
                                                                                                        <label for="suppPric">Stock Actual</label>
                                                                                                        <input type="number" class="form-control" name="markup" ng-attr-min="@{{minNumber}}" string-to-number placeholder="0.00"  ng-model="variant.stock[$index].stockActual" ng-disabled="!variant.track" step="0.1">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-2">
                                                                                                    <div class="form-group" >
                                                                                                        <label for="suppPric">Stock Mínimo</label>
                                                                                                        <input type="number" class="form-control" name="markup" ng-attr-min="@{{minNumber}}" string-to-number placeholder="0.00"  ng-model="variant.stock[$index].stockMin" ng-disabled="!variant.track" step="0.1">
                                                                                                    </div>
                                                                                                </div>

                                                                                                 </span>


                                    </div>
                                </div>
                            </div><!-- /.box-body -->

                        </div>
                        <!--  =============================================================================FIN INVENTARIO===============================================================-->
                        <script>
                            $("#variants").activateBox();
                            $("#price").activateBox();
                            $("#inventory").activateBox();
                        </script>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <button id="btn_generate" data-loading-text="Enviando.." type="submit" class="btn btn-primary" ng-click="updateVariant()">Editar</button>
                        <a href="/products/show/@{{product.id}}" class="btn btn-danger">Cancelar</a>
                    </div>
                </form>
            </div><!-- /.box -->

        </div>
    </div><!-- /.row -->
</section><!-- /.content -->



<!-- =============================Modal de Presentacion ================================ -->

<div class="modal fade bs-example-modal-sm" id="presentation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Añadir Presentación</h4>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="suppPric">Elige Presentación(Unidades, paquetes, six pack.)</label>
                            <select name="" ng-click="selectPres()" class="form-control" id="" ng-model="presentationSelect" ng-options="item as item.nombre+' / '+item.shortname+' / '+item.cant for item in presentations">
                                <option value="">-- Elige Presentación--</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="suppPric">Cambio de Dolar</label>
                        <input type="number" name="table_search" class="form-control pull-rigt" string-to-number ng-model="presentation.cambioDolar" ng-change="calculateCambioDolar()">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control hidden" name="presentation.nombre" ng-model="presentation.nombre">
                        <div class="form-group" >
                            <label for="suppPric">Precio de Compra (S/.)</label>
                            <input type="number" class="form-control" name="suppPric1" string-to-number placeholder="0.00" ng-model="presentation.suppPri" ng-change="calculateSuppPric()" step="0.1">
                            <label for="suppPric">Precio de Compra ($USS)</label>
                            <input type="number" class="form-control" name="suppPriDolar" string-to-number placeholder="0.00" ng-model="presentation.suppPriDol" ng-change="calculateSuppPricDol()" step="0.1">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" > <label for="suppPric">% de Ganancia</label> <input type="number" class="form-control" name="markup1" string-to-number placeholder="0.00" ng-model="presentation.markup" ng-change="calculateMarkup()" step="0.1">
                            <label for="suppPric">Cant. de Ganancia</label>
                            <input type="number" class="form-control" name="markup1" string-to-number placeholder="0.00" ng-model="presentation.markupCant" ng-change="calculateMarkupCant()" step="0.1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" >
                            <label for="suppPric">Precio de Venta</label>
                            <input type="number" class="form-control" name="price1" string-to-number placeholder="0.00" ng-model="presentation.price" ng-change="calculatePrice()" step="0.1">
                        </div>
                    </div>
                </div>
                <h3>Descuentos</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" >
                            <label for="suppPric">% Descuento</label>
                            <input type="number" class="form-control" name="" string-to-number placeholder="0.00" ng-model="presentation.dscto" ng-change="calculateDscto()" step="0.1">
                            <label for="suppPric">Cant Descuento</label>
                            <input type="number" class="form-control" name="" string-to-number placeholder="0.00" ng-model="presentation.dsctoCant" ng-change="calculateDsctoCant()" step="0.1">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="suppPric">% PVP</label>
                        <input type="number" class="form-control" string-to-number  ng-model="presentation.pvp" step="0.1" ng-change="calculatePVP()">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                <h4>Descuentos por Rango</h4></div> <div class="col-md-4"><h4><input type="checkbox" ng-model="presentation.activateDsctoRange"> Activar</h4></div>
                </div>
                <div class="row" ng-show="presentation.activateDsctoRange">
                    <div class="col-md-4">

                        <div class="form-group" >
                            <label for="suppPric">Fecha de Inicio</label>
                            <input type="date" class="form-control" ng-model="presentation.fecIniDscto" ng-change="">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" >
                            <label for="suppPric">Fecha de Fin</label>
                            <input type="date" class="form-control" ng-model="presentation.fecFinDscto" ng-change="">

                        </div>
                    </div>

                </div>

                <div class="row"  ng-show="presentation.activateDsctoRange">
                    <div class="col-md-4">
                        <div class="form-group" >
                            <label for="suppPric">% Descuento</label>
                            <input type="number" class="form-control" name="" string-to-number placeholder="0.00" ng-model="presentation.dsctoRange" ng-change="calculateDsctoRange()" step="0.1">
                            <label for="suppPric">Cant Descuento</label>
                            <input type="number" class="form-control" name="" string-to-number placeholder="0.00" ng-model="presentation.dsctoCantRange" ng-change="calculateDsctoCantRange()" step="0.1">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" >
                            <label for="suppPric">% PVP (Rango)</label>
                            <input type="number" class="form-control" string-to-number ng-model="presentation.pvpRange" ng-change="calculatePVPRange()">

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" ng-click="AddPres()" data-dismiss="modal" ng-hide="presentation.edit">Grabar Cambios</button>
                <button type="button" class="btn btn-primary" ng-click="UpdatePres()" data-dismiss="modal" ng-show="presentation.edit">Editar Cambios</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- ======================================================================================== -->


<!-- =============================Modal CREATE de Presentacion ================================ -->

<div class="modal fade bs-example-modal-sm" id="createpresentation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Crear Presentación</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-4">
                        <input type="hidden" class="form-control" name="preAdd.preBase_id" ng-model="variant.presentation_base">
                        <div class="form-group" >
                            <label for="suppPric">Nombre</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Docena" ng-model="preAdd.nombre">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" > <label for="suppPric">Shortname</label>
                            <input type="text" class="form-control" name="shortname" placeholder="DO12" ng-model="preAdd.shortname">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" >
                            <label for="suppPric">Equiv (@{{ variant.presentation_base_object.nombre }})</label>
                            <input type="number" class="form-control" name="equiv" placeholder="12.00" ng-model="preAdd.cant" min="0">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" ng-click="createPres(variant.presentation_base)" data-dismiss="modal">Crear</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- ======================================================================================== -->

<!-- =============================Modal de Atributo ================================ -->
<script type="text/ng-template" id="myModalContent5.html">
    <div class="modal-header">
        <h3 class="modal-title">Crear Atributo</h3>
    </div>
    <div class="modal-body">


        <form name="atributCreateForm" role="form" novalidate>
            <div class="box-body">
                <div class="callout callout-danger" ng-show="errors">
                    <ul>
                        <li ng-repeat="row in errors track by $index"><strong >@{{row}}</strong></li>
                    </ul>
                </div>

                <div class="form-group" ng-class="{true: 'has-error'}[ atributCreateForm.nombre.$error.required && atributCreateForm.$submitted || atributCreateForm.nombre.$dirty && atributCreateForm.nombre.$invalid]">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre" ng-model="atribut.nombre" required>
                    <label ng-show="atributCreateForm.$submitted || atributCreateForm.nombre.$dirty && atributCreateForm.nombre.$invalid">
                        <span ng-show="atributCreateForm.nombre.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                    </label>
                </div>
                <div class="form-group" ng-class="{true: 'has-error'}[ atributCreateForm.shortname.$error.required && atributCreateForm.$submitted || atributCreateForm.shortname.$dirty && atributCreateForm.shortname.$invalid]">
                    <label for="nombre">ShortName</label>
                    <input type="text" class="form-control" name="shortname" placeholder="ShortName" ng-model="atribut.shortname" required>
                    <label ng-show="atributCreateForm.$submitted || atributCreateForm.shortname.$dirty && atributCreateForm.shortname.$invalid">
                        <span ng-show="atributCreateForm.shortname.$error.required"><i class="fa fa-times-circle-o"></i>Requerido.</span>
                    </label>
                </div>
                <div class="form-group" >
                    <label for="descripcion">Descripcion</label>
                      <textarea type="descripcion" class="form-control" name="descripcion" placeholder="Descripcion"
                                ng-model="atribut.descripcion" rows="4" cols="50"></textarea>
                </div>

            </div><!-- /.box-body -->

        </form>



    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="button" ng-click="createAttribute()">OK</button>
        <button class="btn btn-warning" type="button" ng-click="cancelAttribute()">Cancelar</button>
    </div>
</script>
<!-- =============================END Modal de Atributo ================================ -->