<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\ListService;

class ListServiceRepo extends BaseRepo{
    public function getModel()
    {
        return new ListService;
    }

    public function search($q)
    {
        $brands =ListService::where('nomServicio','like', $q.'%')
                    ->orWhere('tipo','like',$q.'%')
                    ->orWhere('descripcion','like',$q.'%')
                    ->orWhere('CostoAprox','=',$q)
                    //->with(['customer','employee'])
                    ->paginate(15);
        return $brands;
    }
    public function validarNoRepit($text){
        $brands =ListService::where('nombre','=', $text)
                    ->orWhere('shortname','=', $text)
                    //->with(['customer','employee'])
                    ->first();
        return $brands;
    }
    public function misDatos($store,$q){
      $datos = \DB::table('listServices')
                            ->select(\DB::raw('nomServicio as NombreAtributos, costoAprox as precioProducto,
                                id as listService_id'
                                ))
                            ->where('store_id','=',$store)  
                            ->where('nomServicio','like',$q.'%')
                                                 
                            ->get();
            return $datos;
    }
}