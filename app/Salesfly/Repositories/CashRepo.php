<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\Cash;

class CashRepo extends BaseRepo{
    
    public function getModel()
    {
        
        return new Cash;
    }

    public function search($q)
    {
        if($q==0){
            $q='%%';
        }
        $cashes =Cash::join("users","cashes.user_id","=","users.id")
                    ->select("cashes.*","users.name")

                    ->where('cashes.cashHeader_id','like', $q)
                    ->orWhere('users.name','like', $q)
                    ->orWhere('cashes.fechaInicio','like','%'.$q.'%')

                    //->orderBy('id', 'desc')
                    //with(['customer','employee'])
                    ->paginate(15);
        return $cashes;
    }
    public function search1($q)
    {
        if($q==0){
            $q='%%';
        }
        $cashes =Cash::join("users","cashes.user_id","=","users.id")
                    ->select("cashes.*","users.name")

                    ->where('cashes.cashHeader_id','like', $q)
                    ->orWhere('users.name','like', $q)
                    ->orWhere('cashes.fechaInicio','like','%'.$q.'%')

                    ->orderBy('id', 'desc')
                    //with(['customer','employee'])
                    ->paginate(15);
        return $cashes;
    }
    public function searchuserincaja($id){
        $cashes =Cash::select("id")
                     ->where('user_id','=', $id)
                     ->where('estado','=',1)
                    //with(['customer','employee'])
                    ->first();
        return $cashes;
    }
     public function searchuserincaja2($id,$user){
        $cashes =Cash::where('user_id','=', $user)
                     ->where('id','=',$id)
                    //with(['customer','employee'])
                    ->first();
        return $cashes;
    }
    public function searchuserincaja1($idCaja,$id){
        $cashes =Cash::where('id','=', $idCaja)
                     ->where('user_id','=',$id)
                     ->where('estado','=',1)
                    //with(['customer','employee'])
                    ->first();
        return $cashes;
    }
    public function paginarCashes($q){
           $cashes =Cash::join("users","cashes.user_id","=","users.id")
                    ->select("cashes.*","users.name")
                    ->orderBy('id', 'desc')
                    ->paginate($q);
        return $cashes;
    }
    public function paginar1($q){
           $cashes =Cash::join("users","cashes.user_id","=","users.id")
                    ->select("cashes.*","users.name")
                    ->orderBy('id', 'desc')
                    ->paginate($q);
        return $cashes;
    }
    
} 