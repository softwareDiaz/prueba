<?php

namespace Salesfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Salesfly\Salesfly\Repositories\ListServiceRepo;
use Salesfly\Salesfly\Managers\ListServiceManager;

class ListServicesController extends Controller {

    protected $listServiceRepo;

    public function __construct(ListServiceRepo $listServiceRepo)
    {
        $this->listServiceRepo = $listServiceRepo;
    }

    public function index()
    {
        return View('listServices.index');
    }

    public function all()
    {
        $listServices = $this->listServiceRepo->paginate(15);
        return response()->json($listServices);
        //var_dump($listServices);
    }

    public function paginatep(){
        $listServices = $this->listServiceRepo->paginate(15);
        return response()->json($listServices);
    }


    public function form_create()
    {
        return View('listServices.form_create');
    }

    public function form_edit()
    {
        return View('listServices.form_edit');
    }

    public function create(Request $request)
    {
        $listServices = $this->listServiceRepo->getModel();
        //var_dump($request->all());
        //die();
        $manager = new ListServiceManager($listServices,$request->all());
        //print_r($manager); die();
        $manager->save();
        //Event::fire('update.listService',$listService->all());

        return response()->json(['estado'=>true]);
    }

    public function find($id)
    {
        $listService = $this->listServiceRepo->find($id);
        return response()->json($listService);
    }

    public function edit(Request $request)
    {
        $listService = $this->listServiceRepo->find($request->id);
        //var_dump($listService);
        //die(); 
        $manager = new listServiceManager($listService,$request->all());
        $manager->save();

        //Event::fire('update.listService',$listService->all());
        return response()->json(['estado'=>true]);
    }

    public function destroy(Request $request)
    {
        $listService= $this->listServiceRepo->find($request->id);
        $listService->delete();
        //Event::fire('update.listService',$listService->all());
        return response()->json(['estado'=>true, 'nombre'=>$listService->nombre]);
    }

    public function search($q)
    {
        //$q = Input::get('q');
        $listServices = $this->listServiceRepo->search($q);

        return response()->json($listServices);
    }
    public function validalistServicename($text){
        
        $listServices = $this->listServiceRepo->validarNoRepit($text);

        return response()->json($listServices);
    }
    public function misDatos($store,$q)
    {
        $listServices = $this->listServiceRepo->misDatos($store, $q);
        return response()->json($listServices);
    }
}