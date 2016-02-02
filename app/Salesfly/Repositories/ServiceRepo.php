<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\Service;

class ServiceRepo extends BaseRepo{
    public function getModel()
    {
        return new Service;
    }
    public function numeroServicio(){
        $brands =Service::orderBy('numeroServicio', 'desc')
                    //->with(['customer','employee'])
                    ->first();
        return $brands;
    }
    public function paginate($q){
           $cashes =Service::orderBy('id', 'desc')
                    ->paginate($q);
        return $cashes;
    }
    public function search($q)
    {
        $brands =Service::where('numeroServicio','like', $q.'%')
                    ->orWhere('cliente','like',$q.'%')
                    ->orWhere('empresa','like',$q.'%')
                    ->orderBy('id', 'desc')
                    //->with(['customer','employee'])
                    ->paginate(15);
        return $brands;
    }
}