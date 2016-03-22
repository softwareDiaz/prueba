<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\DetServices;

class DetServicesRepo extends BaseRepo{
    
    public function getModel()
    {
        
        return new DetServices;
    }

    public function search($q)
    {
        $detSales = \DB::table('detServices')->leftjoin('detPres','detServices.detPre_id','=','detPres.id')
                    ->leftjoin('variants','detPres.variant_id','=','variants.id')
                    ->leftjoin('products','variants.product_id','=','products.id')
                    ->leftjoin('stock','variants.id','=','stock.variant_id')
                    ->leftjoin('warehouses','warehouses.id','=','stock.warehouse_id')
                    ->leftjoin('stores','stores.id','=','warehouses.store_id')
                    ->leftjoin('presentation as T1','T1.id','=','products.presentation_base')
                    ->leftjoin('equiv','equiv.preFin_id','=','T1.id')
                    ->leftjoin('materials','products.material_id','=','materials.id')
                    ->leftjoin('presentation as T2','T2.id','=','detPres.presentation_id')

                    ->leftjoin('listServices','listServices.id','=','detServices.listService_id')

                    ->select(\DB::raw('detServices.*, variants.sku as SKU ,detPres.id as detPre_id,variants.id as vari ,
                                IF(products.hasVariants=1 , CONCAT(products.nombre,"(",(SELECT GROUP_CONCAT(CONCAT(atributes.shortname,":",detAtr.descripcion) SEPARATOR " /")
                                 FROM variants
                                LEFT JOIN detAtr ON detAtr.variant_id = variants.id
                                LEFT JOIN atributes ON atributes.id = detAtr.atribute_id
                                where variants.id=vari
                                GROUP BY variants.id),")"),  products.nombre ) as NombreProducto,
                                materials.nombre as Material,
                              warehouses.nombre as Almacen,stock.stockActual as Stock,IF(detServices.detPre_id is NULL ,listServices.costoAprox, detPres.price) as precioProducto,

                                detPres.fecIniDscto as FechaInicioDescuento,detPres.fecFinDscto as FechaFinDescuento,
                                detPres.dsctoRange as DescuentoConFecha,detPres.dscto as DescuentoSinFecha,

                              stock.stockPedidos as stockPedidos,stock.stockSeparados as stockSeparados,
                               IF(detServices.detPre_id is NULL, listServices.nomServicio , IF(products.hasVariants=1 , CONCAT(variants.codigo," - ",products.nombre,"/ ",(SELECT GROUP_CONCAT(CONCAT(atributes.shortname,":",detAtr.descripcion) SEPARATOR " /") FROM variants
                                LEFT JOIN detAtr ON detAtr.variant_id = variants.id
                                LEFT JOIN atributes ON atributes.id = detAtr.atribute_id
                                where variants.id=vari
                                GROUP BY variants.id)),  CONCAT(variants.codigo," - ",products.nombre) )) as NombreAtributos , T1.nombre as Base, T2.nombre as Presentacion, products.presentation_base, warehouses.id as idAlmacen,
                            equiv.cant as equivalencia, variants.favorite as favorite, variants.codigo as NombreAtributo'))


                    ->where('service_id','=', $q)

                    //with(['customer','employee'])
                    ->get();
        return $detSales;
    }


    public function misDatos($store,$were,$q){
      $datos = \DB::table('products')->leftjoin('materials','products.material_id','=','materials.id')
                           ->leftjoin('variants','products.id','=','variants.product_id')
                            ->leftjoin('stock','variants.id','=','stock.variant_id')
                            ->leftjoin('warehouses','warehouses.id','=','stock.warehouse_id')
                            ->leftjoin('stores','stores.id','=','warehouses.store_id')
                            ->leftjoin('presentation as T1','T1.id','=','products.presentation_base')
                            ->leftjoin('equiv','equiv.preFin_id','=','T1.id')
                            ->join('detPres','detPres.variant_id','=','variants.id')
                            ->join('presentation as T2','T2.id','=','detPres.presentation_id')
                            ->select(\DB::raw('variants.sku as SKU ,detPres.id as detPre_id,variants.id as vari ,

                                IF(products.hasVariants=1 , CONCAT(products.nombre,"(",(SELECT GROUP_CONCAT(CONCAT(atributes.shortname,":",detAtr.descripcion) SEPARATOR " /")
                                 FROM variants
                                LEFT JOIN detAtr ON detAtr.variant_id = variants.id
                                LEFT JOIN atributes ON atributes.id = detAtr.atribute_id
                                where variants.id=vari
                                GROUP BY variants.id),")"),  products.nombre ) as NombreProducto,
                                materials.nombre as Material,
                              warehouses.nombre as Almacen,stock.stockActual as Stock,detPres.price as precioProducto,

                                detPres.fecIniDscto as FechaInicioDescuento,detPres.fecFinDscto as FechaFinDescuento,
                                detPres.dsctoRange as DescuentoConFecha,detPres.dscto as DescuentoSinFecha,

                              stock.stockPedidos as stockPedidos,stock.stockSeparados as stockSeparados,
                               IF(products.hasVariants=1 , CONCAT(variants.codigo," - ",products.nombre,"/ ",(SELECT GROUP_CONCAT(CONCAT(atributes.shortname,":",detAtr.descripcion) SEPARATOR " /") FROM variants
                                LEFT JOIN detAtr ON detAtr.variant_id = variants.id
                                LEFT JOIN atributes ON atributes.id = detAtr.atribute_id
                                where variants.id=vari
                                GROUP BY variants.id)),  CONCAT(variants.codigo," - ",products.nombre) ) as NombreAtributos , T1.nombre as Base, T2.nombre as Presentacion, products.presentation_base, warehouses.id as idAlmacen,
                            equiv.cant as equivalencia, variants.favorite as favorite, variants.codigo as NombreAtributo'))
                             
                              //'T1.nombre as Base')
                            ->where('stores.id','=',$store)
                            ->where('warehouses.id','=',$were)
                            ->where('variants.codigo','like', $q.'%')
                            ->where('T2.base','=','1')
                            /////--------------------
                            ->where('products.estado','=','1')
                            ->where('variants.estado','=','1')
                            /////--------------------
                            //->where('variants.estado','=','1')
                            //->where('products.estado','=','1')
                            ->orWhere('stores.id','=',$store)
                            ->where('warehouses.id','=',$were)
                            ->where('products.nombre','like', $q.'%')
                            ->where('T2.base','=','1')
                            /////--------------------
                            ->where('products.estado','=','1')
                            ->where('variants.estado','=','1')
                            /////--------------------
                            //->where('variants.estado','=','1')
                            //->where('products.estado','=','1')
                            /////--------------------
                            ->groupBy('variants.id')
                            ->get();
            return $datos;
    }

    
} 