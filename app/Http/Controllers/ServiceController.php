<?php

namespace Salesfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Salesfly\Salesfly\Repositories\ServiceRepo;
use Salesfly\Salesfly\Managers\ServiceManager;

class ServiceController extends Controller {

    protected $serviceRepo;

    public function __construct(ServiceRepo $serviceRepo)
    {
        $this->serviceRepo = $serviceRepo;
    }

    public function index()
    {
        return View('services.index');
    }

    public function all()
    {
        $services = $this->serviceRepo->paginate(15);
        return response()->json($services);
    }

    public function paginatep(){
        $services = $this->serviceRepo->paginate(15);
        return response()->json($services);
    }


    public function form_create()
    {
        return View('services.form_create');
    }

    public function form_edit()
    {
        return View('services.form_edit');
    }

     public function form_editD()
    {
        return View('services.form_editD');
    }

    public function create(Request $request)
    {
        $request->merge(array('employee_id' => auth()->user()->id));
        
        $services = $this->serviceRepo->getModel();
        //var_dump($request->all());
        //die();

        $manager = new ServiceManager($services,$request->all());
        //print_r($manager); die();
        $manager->save();
        //Event::fire('update.brand',$brand->all());

        return response()->json(['estado'=>true, 'nombre'=>$services->nombre]);
    }

    public function find($id)
    {
        $brand = $this->serviceRepo->find($id);
        return response()->json($brand);
    }

    public function edit(Request $request)
    {
        $brand = $this->serviceRepo->find($request->id);
        //var_dump($brand);
        //die(); 
        $manager = new ServiceManager($brand,$request->all());
        $manager->save();

        //Event::fire('update.brand',$brand->all());
        return response()->json(['estado'=>true, 'nombre'=>$brand->nombre]);
    }

    public function destroy(Request $request)
    {
        $brand= $this->serviceRepo->find($request->id);
        $brand->delete();
        //Event::fire('update.brand',$brand->all());
        return response()->json(['estado'=>true, 'nombre'=>$brand->nombre]);
    }

    public function search($q)
    {
        //$q = Input::get('q');
        $services = $this->serviceRepo->search($q);

        return response()->json($services);
    }
    public function validaBrandname($text){
        
        $services = $this->serviceRepo->validarNoRepit($text);

        return response()->json($services);
    }

    public function numeroServicio()
    {
        $services = $this->serviceRepo->numeroServicio();
        return response()->json($services);
    }
}