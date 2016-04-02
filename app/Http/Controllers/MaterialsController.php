<?php

namespace Salesfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Salesfly\Salesfly\Repositories\MaterialRepo;
use Salesfly\Salesfly\Managers\MaterialManager;

use Salesfly\Salesfly\Repositories\PriceDolarRepo;
use Salesfly\Salesfly\Managers\PriceDolarManager;

class MaterialsController extends Controller {

    protected $materialRepo;

    public function __construct(MaterialRepo $materialRepo,PriceDolarRepo $priceDolarRepo)
    {
        $this->materialRepo = $materialRepo;
        $this->priceDolarRepo=$priceDolarRepo;
    }

    public function index()
    {
        return View('materials.index');
    }

    public function all()
    {
        $materials = $this->materialRepo->paginate(15);
        return response()->json($materials);
        //var_dump($materials);
    }

    public function paginatep(){
        $materials = $this->materialRepo->paginate(15);
        return response()->json($materials);
    }
    public function paginatep2(){
        $pricedolar = $this->priceDolarRepo->paginarGroup(15);
        return response()->json($pricedolar);
    }
    public function editPaginar($mes){
           $pricedolar = $this->priceDolarRepo->editPaginar($mes);
        return response()->json($pricedolar);
    }
    public function form_create()
    {
        return View('materials.form_create');
    }
    public function form_preDolar()
    {
        return View('materials.form_preDolar');
    }
    public function form_listPreciDolar()
    {
        return View('materials.form_listPreciDolar');
    }
    public function form_edit()
    {
        return View('materials.form_edit');
    }
    public function form_editPreDolar()
    {
        return View('materials.form_editPreDolar');
    }
    public function preDolar(Request $request)
    {
       //$pricedolar = $this->priceDolarRepo->getModel();
        //var_dump($request->all());
        //die();
    
       foreach ($request->all() as $object) {
           $pricedolar = new PriceDolarRepo;
           $manager = new PriceDolarManager($pricedolar->getModel(),$object);
        //print_r($manager); die();
           $manager->save();
       }
        
        //Event::fire('update.material',$material->all());

        return response()->json(['estado'=>true]); 
    }
    public function create(Request $request)
    {
        $materials = $this->materialRepo->getModel();
        //var_dump($request->all());
        //die();
        $manager = new MaterialManager($materials,$request->all());
        //print_r($manager); die();
        $manager->save();
        //Event::fire('update.material',$material->all());

        return response()->json(['estado'=>true, 'nombre'=>$materials->nombre]);
    }

    public function find($id)
    {
        $material = $this->materialRepo->find($id);
        return response()->json($material);
    }
    public function editPredolar(Request $request){
        $precidolar = $this->priceDolarRepo->find($request->id);
        //var_dump($material);
        //die(); 
        $manager = new PriceDolarManager($precidolar,$request->all());
        $manager->save();

        //Event::fire('update.material',$material->all());
        return response()->json(['estado'=>true]);
    }
    public function edit(Request $request)
    {
        $material = $this->materialRepo->find($request->id);
        //var_dump($material);
        //die(); 
        $manager = new MaterialManager($material,$request->all());
        $manager->save();

        //Event::fire('update.material',$material->all());
        return response()->json(['estado'=>true, 'nombre'=>$material->nombre]);
    }

    public function destroy(Request $request)
    {
        $material= $this->materialRepo->find($request->id);
        $material->delete();
        //Event::fire('update.material',$material->all());
        return response()->json(['estado'=>true, 'nombre'=>$material->nombre]);
    }

    public function search($q)
    {
        //$q = Input::get('q');
        $materials = $this->materialRepo->search($q);

        return response()->json($materials);
    }
}