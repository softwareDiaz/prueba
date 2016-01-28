<?php
namespace Salesfly\Salesfly\Managers;
class ListServiceManager extends BaseManager {

    public function getRules()
    {
        $rules = ['nomServicio'=>'required',
                            'descripcion'=>'required',
                            'tipo'=>'required',
                            'estado'=>'required',
                            'costoAprox'=>'between:0,9999999999.00'];
        return $rules;
    }}