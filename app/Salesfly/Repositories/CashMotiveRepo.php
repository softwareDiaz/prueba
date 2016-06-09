<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\CashMotive;

class CashMotiveRepo extends BaseRepo{
    
    public function getModel()
    {
        
        return new CashMotive;
    }

    public function search($q)
    {
        $cashMotives =CashMotive::where('tipo','=', $q)
                ->where('id','!=',1)
                    //with(['customer','employee'])
                    ->paginate(15);
        return $cashMotives;
    }
    public function searchMotive($q)
    {
        $cashMotive =CashMotive::where('id','=', $q)
                    //with(['customer','employee'])
                    ->paginate(15);
        return $cashMotive;
    }
    public function traerNombre($q)
    {
        $cashMotive =CashMotive::where('id','=', $q)
        ->select("nombre")
                    //with(['customer','employee'])
                    ->first();
        return $cashMotive;
    }

} 