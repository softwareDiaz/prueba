<?php
namespace Salesfly\Salesfly\Managers;
class PriceDolarManager extends BaseManager {

    public function getRules()
    {
        $rules = [              
            'fecha'=> 'required',
            'fecha2'=> 'required',
            'preDolar'=> 'required',
            'mes'=>'required'
                  ];
        return $rules;
    }}