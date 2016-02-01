<?php
namespace Salesfly\Salesfly\Managers;
class DetServicesManager extends BaseManager {

    public function getRules() 
    {
        $rules = [              
            'precioProducto'=> '',
            'precioVenta'=> '',
            'cantidad'=> '',
            'descuento'=> '',
            'subTotal'=> '',
            'service_id'=> '',
            'detPre_id'=> '',
            'listService_id'=> ''];
        return $rules;
    }}