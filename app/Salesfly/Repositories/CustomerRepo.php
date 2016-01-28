<?php
namespace Salesfly\Salesfly\Repositories;
use Salesfly\Salesfly\Entities\Customer;

class CustomerRepo extends BaseRepo{
    public function getModel()
    {
        return new Customer;
    }

    public function search($q)
    {
        $customers =Customer::where('nombres','like', $q.'%')
                    ->orWhere('apellidos','like',$q.'%')
                    ->orWhere('empresa','like',$q.'%')
                    ->orWhere('ruc','like',$q.'%')
                    //->with(['customer','employee'])
                    ->paginate(15);
        return $customers;
    }
    public function searchVenta($q)
    {
        $customers =Customer::select(\DB::raw('id,dni,nombres,apellidos,ruc,direccFiscal,empresa,fijo,movil,empresa,CONCAT(nombres,"-",apellidos,"-",empresa) as busqueda'))
                    ->where('nombres','like', $q.'%')
                    ->orWhere('apellidos','like',$q.'%')
                    ->orWhere('empresa','like',$q.'%')
                    ->orWhere('dni','like',$q.'%')
                    ->paginate(15);
        return $customers;
    } 

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
} 