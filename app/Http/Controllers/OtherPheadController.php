<?php

namespace Salesfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Salesfly\Salesfly\Repositories\OtherPheadRepo;
use Salesfly\Salesfly\Managers\OtherPheadManager;

use Salesfly\Salesfly\Repositories\OtherPdetailRepo;
use Salesfly\Salesfly\Managers\OtherPdetailManager;

class OtherPheadController extends Controller {

    protected $brandRepo;

    public function __construct(OtherPheadRepo $otherPheadRepo,OtherPdetailRepo $otherPdetailRepo)
    {
        $this->otherPheadRepo = $otherPheadRepo;
        $this->otherPdetailRepo= $otherPdetailRepo;
    }

    public function index()
    {
        return View('otherPheads.index');
    }

    public function all()
    {
        $brands = $this->brandRepo->paginate(15);
        return response()->json($brands);
        //var_dump($brands);
    }

    public function paginatep(){
        $otherPhead = $this->otherPheadRepo->paginate(15);
        return response()->json($otherPhead);
    }


    public function form_create()
    {
        return View('otherPheads.form_create');
    }

    public function form_edit()
    {
        return View('otherPheads.form_edit');
    }

    public function create(Request $request)
    {
        //var_dump($request->all());die();
        $otherPhead = $this->otherPheadRepo->getModel();
        $var=$request->detalles;
        $manager = new OtherPheadManager($otherPhead,$request->all());
        //print_r($manager); die();
        $manager->save();
        //var_dump($request->all());
        //die();
        $idHead=$otherPhead->id;
        foreach ($var as $object) {
            $object['otherPhead_id']=$idHead;
            $otherPdetailRepo = new OtherPdetailRepo;
            $otherPdetail=new OtherPdetailManager($otherPdetailRepo->getModel(),$object);
            $otherPdetail->save();
        }
      
        //Event::fire('update.brand',$brand->all());

        return response()->json(['estado'=>true]);
    }

    public function find($id)
    {
        $otherPhead = $this->otherPheadRepo->find($id);
        return response()->json($otherPhead);
    }
     public function datos($id)
    {
        $otherPdetail = $this->otherPdetailRepo->datos($id);
        return response()->json($otherPdetail);
    }
    public function edit(Request $request)
    {
        $otherPhead = $this->otherPheadRepo->find($request->id);
        $var=$request->detalles;
        //var_dump($brand);
        //die(); 
        $manager = new OtherPheadManager($otherPhead,$request->all());
        $manager->save();
        $idHead=$otherPhead->id;
        $otherPdetail = $this->otherPdetailRepo->datos($request->id);
        foreach ($otherPdetail as $object) {
            $otherPdetail= $this->otherPdetailRepo->find($object["id"]);
            $otherPdetail->delete();
        }
        foreach ($var as $object) {
            $object['otherPhead_id']=$idHead;
            $otherPdetailRepo = new OtherPdetailRepo;
            $otherPdetail=new OtherPdetailManager($otherPdetailRepo->getModel(),$object);
            $otherPdetail->save();
        }
        //Event::fire('update.brand',$brand->all());
        return response()->json(['estado'=>true]);
    }

    public function destroy(Request $request)
    {
        $otherPdetail = $this->otherPdetailRepo->datos($request->id);
        foreach ($otherPdetail as $object) {
            $otherPdetail= $this->otherPdetailRepo->find($object["id"]);
            $otherPdetail->delete();
        }
        $otherPhea= $this->otherPheadRepo->find($request->id);
        $otherPhea->delete();
        
        //Event::fire('update.brand',$brand->all());
        return response()->json(['estado'=>true]);
    }

    public function search($q)
    {
        //$q = Input::get('q');
        $brands = $this->brandRepo->search($q);

        return response()->json($brands);
    }
    public function validaBrandname($text){
        
        $brands = $this->brandRepo->validarNoRepit($text);

        return response()->json($brands);
    }
}