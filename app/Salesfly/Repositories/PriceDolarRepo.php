<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\PriceDolar;

class PriceDolarRepo extends BaseRepo{
    public function getModel()
    {
        return new PriceDolar;
    }

    public function search($q)
    {
        $brands =Brand::where('nombre','like', $q.'%')
                    ->orWhere('shortname','like',$q.'%')
                    //->with(['customer','employee'])
                    ->paginate(15);
        return $brands;
    }
    public function validarNoRepit($text){
        $brands =Brand::where('nombre','=', $text)
                    ->orWhere('shortname','=', $text)
                    //->with(['customer','employee'])
                    ->first();
        return $brands;
    }
    public function paginarGroup($q){
        $pricedolar =PriceDolar::groupBY('mes')
                    ->paginate($q);
        return $pricedolar;
    }
    public function editPaginar($mes){
        $pricedolar =PriceDolar::where("mes","=",$mes)
                      
                    ->get();
        return $pricedolar;
    }
}