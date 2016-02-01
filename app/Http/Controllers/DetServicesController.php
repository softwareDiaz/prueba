<?php

namespace Salesfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Salesfly\Salesfly\Repositories\DetServicesRepo;
use Salesfly\Salesfly\Managers\DetServicesManager;

class DetServicesController extends Controller {

    protected $detServicesRepo;

    public function __construct(DetServicesRepo $detServicesRepo)
    {
        $this->detServicesRepo = $detServicesRepo;
    }

    public function index()
    {
        return View('detServices.index');
    }

    public function all()
    {
        $detServices = $this->detServicesRepo->paginate(15);
        return response()->json($detServices);
        //var_dump($detServices);
    }

    public function paginatep(){
        $detServices = $this->detServicesRepo->paginate(15);
        return response()->json($detServices);
    }


    public function form_create()
    {
        return View('detServices.form_create');
    }

    public function form_edit()
    {
        return View('detServices.form_edit');
    }

    public function create(Request $request)
    {
        //return $request;
        $detServices = $this->detServicesRepo->getModel();
        //var_dump($request->all());
        //die();
        $manager = new DetServicesManager($detServices,$request->all());
        //print_r($manager); die();
        $manager->save();
        //Event::fire('update.brand',$brand->all());

        return response()->json(['estado'=>true]);
    }

    public function find($id)
    {
        $brand = $this->detServicesRepo->find($id);
        return response()->json($brand);
    }

    public function edit(Request $request)
    {
        $brand = $this->detServicesRepo->find($request->id);
        //var_dump($brand);
        //die(); 
        $manager = new DetServicesManager($brand,$request->all());
        $manager->save();

        //Event::fire('update.brand',$brand->all());
        return response()->json(['estado'=>true, 'nombre'=>$brand->nombre]);
    }

    public function destroy(Request $request)
    {
        $brand= $this->detServicesRepo->find($request->id);
        $brand->delete();
        //Event::fire('update.brand',$brand->all());
        return response()->json(['estado'=>true, 'nombre'=>$brand->nombre]);
    }

    public function search($q)
    {
        //$q = Input::get('q');
        $detServices = $this->detServicesRepo->search($q);

        return response()->json($detServices);
    }
    public function validaBrandname($text){
        
        $detServices = $this->detServicesRepo->validarNoRepit($text);

        return response()->json($detServices);
    }
}