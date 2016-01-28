<?php

namespace Salesfly\Salesfly\Managers;
class ServiceManager extends BaseManager {

    public function getRules()
    {
        $rules = [  
                    'numeroServicio'=>'required',
                    'fechaServicio'=>'required',
                    'tipo' => 'required',
                    'cliente' => '',
                    'ruc'=> '',
                    'direcion' => '',
                    'telefono' => 'required',
                    'empresa' => '',
                    'descripcion'=> '',
                    'modelo'=> 'required',
                    'serie'=> 'required',
                    'accesorios'=> '',
                    'diagnostico'=> '',
                    'accionCorrectiva'=> '',
                    'estado'=> '',
                    'customer_id'=> 'required',
                    'employee_id'=> 'required',
                    'ordenTrabajo'=> '',
                    'user_id'=> ''
                  ];
        return $rules;
    }
}