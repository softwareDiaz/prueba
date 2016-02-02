<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\User;

class UserRepo extends BaseRepo{
    public function getModel()
    {
        return new User;
    }

    public function search($q)
    {
        $types =User::where('nombre','like', $q.'%')
                    
                    //->with(['customer','employee'])
                    ->paginate(15);
        return $types;
    }
    public function searchUser($q)
    {
        $types =User::select('id','nombre')
                    ->get();
        return $types;
    }
    public function traerUltimoID()
    {
        $types =User::select('id')
                     ->orderBy('id','desc')
                    ->first();
        return $types;
    }
    public function all(){
        $stores = Store::all();
        return $stores;
    }

} 