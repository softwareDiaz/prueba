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
}