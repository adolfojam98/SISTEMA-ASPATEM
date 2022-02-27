<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\User;
  
class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('changePassword');
    } 
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'contrasena_actual' => ['required', new MatchOldPassword],
            'nueva_contrasena' => ['required'],
            'confirma_nueva_contrasena' => ['same:nueva_contrasena'],
        ]);
   
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->nueva_contrasena)]);
   
      return 'Contraseña cambiada correctamente';
    }
}