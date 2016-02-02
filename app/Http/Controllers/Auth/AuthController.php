<?php

namespace Salesfly\Http\Controllers\Auth;

use Illuminate\Support\Facades\View;
use Salesfly\User;
use Salesfly\Salesfly\Entities\Store;
use Salesfly\Salesfly\Repositories\UserRepo;
use Salesfly\Salesfly\Managers\UserManager;
use Validator;
use Salesfly\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout','indexU','all','paginate','form_create','form_edit','store_select','postRegister','search','find','edit']]);
        //$this->middleware('auth',['only' => 'index']);
    }
     
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6',
            'store_id' => 'required|integer',
            'role_id' => 'required|integer',
            'estado' => 'required|integer',
            'image' => ''
        ]);
    }

    /**
     * Get a validator for an incoming edit request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorEdit(array $data)
    {
        $user = User::find($data['id']);
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'store_id' => 'required|integer',
            'role_id' => 'required|integer',
            'estado' => 'required|integer',
            'image' => ''
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function get_string_between($string, $start, $end){
    $string = " ".$string;
     $ini = strpos($string,$start);
     if ($ini == 0) return "";
     $ini += strlen($start);     
     $len = strpos($string,$end,$ini) - $ini;
     return substr($string,$ini,$len);
}
    protected function create(array $data)
    {
        //var_dump($data['name']);die();
        $userRepo = new UserRepo;
        $user = $userRepo->traerUltimoID();
        //var_dump($user->id);die();
        if(!empty($data['image']) && $data['image'] and substr($data['image'],5,5) === 'image'){
            $imagen = $data['image'];
            $mime = $this->get_string_between($imagen,'/',';');
            $imagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagen));
            Image::make($imagen)->resize(200,200)->save('images/users/'.$user->id.'.'.$mime);
            $data['image']='/images/users/'.$user->id.'.'.$mime;
            //$user->save();
        }else{
            $data['image']='/images/users/default.jpg';
            //$user->save();
        }
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'store_id' => $data['store_id'],
            'role_id' => $data['role_id'],
            'estado' => $data['estado'],
            'image' => $data['image']
        ]);
        //$request['password'] = bcrypt($data['password']),
        /*$userRepo = new UserRepo;
        $user = $userRepo->getModel();
       $manager = new UserManager($user,$data['name'],
             $data['email'],
             bcrypt($data['password']),
             $data['store_id'],
             $data['role_id'],
             $data['estado']);
       $manager->save();
        
        //------------------------------------------------

        if($request['image'] and substr($request['image'],5,5) === 'image'){
            $imagen = $request['image'];
            $mime = $this->get_string_between($imagen,'/',';');
            $imagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagen));
            Image::make($imagen)->resize(200,200)->save('images/users/'.$user->id.'.'.$mime);
            $user->imagen='/images/users/'.$user->id.'.'.$mime;
            $user->save();
        }else{
            $user->imagen='/images/users/default.jpg';
            $user->save();
        }
        //-------------------------------------------------
        
       //$manager = new UserManager($user,$request);
       $manager->save();*/

        //return response()->json(['estado'=>true, 'nombres'=>$employee->nombres]);
    }
    

    /**
     * Update a user instance after edit form.
     *
     * @param  array  $data
     * @return User
     */
    protected function update(User $user,array $data)
    {
        //retorna filas afectadas;
         $userRepo = new UserRepo;
        $user1 = $userRepo->traerUltimoID();
    var_dump($data["name"]);die();
        if(!empty($data['image']) && $data['image'] and substr($data['image'],5,5) === 'image'){
            $imagen = $data['image'];
            $mime = $this->get_string_between($imagen,'/',';');
            $imagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagen));
            Image::make($imagen)->resize(200,200)->save('images/users/'.$user1->id.'.'.$mime);
            $data['image']='/images/users/'.$user1->id.'.'.$mime;
            //$user->save();
        }else{
            $data['image']='/images/users/default.jpg';
            //$user->save();
        }
         $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'store_id' => $data['store_id'],
            'role_id' => $data['role_id'],
            'estado' => $data['estado'],
            'image' => $data['image']
        ]);
        return $user;
    }


    /**
    * funciones creadas por Salesfly
     **/
    protected  function indexU()
    {
        if(\Auth::check()){
            return View('auth.users.index');
        }else{
            return redirect()->to('auth/login');
        }

    }

    protected  function all()
    {
        if(\Auth::check()){
            $users = User::all();
            return response()->json($users);
        }else{
            return redirect()->to('auth/login');
        }
    }

    protected  function paginate(){
        if(\Auth::check()) {
            $users = User::with(array('store'=>function($query){
                $query->select('id','nombreTienda');
            }))->paginate(15);
            /*$users = User::where('name','like','%234')->get();
            foreach($users as $user){
                print_r(response()->json($user->store()->get()));
            }
            $tienda = $user->store()->get();*/
            return response()->json($users);

        }else{
            return redirect()->to('auth/login');
        }
    }
    public function form_create()
    {
        return View('auth.users.form_create');
    }

    public function form_edit()
    {
        return View('auth.users.form_edit');
    }
    public function store_select()
    {
        $stores = Store::lists('nombreTienda','id');
        return response()->json($stores);
    }
    public function search($q){
        $users =User::where('name','like', $q.'%')
            ->orWhere('email','like',$q.'%')
            //->with(['customer','employee'])
            ->paginate(15);
        return $users;
    }
    public function find($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }
}
