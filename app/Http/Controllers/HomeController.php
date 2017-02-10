<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Route;

class HomeController extends Controller
{
    public static function routes()
    {
    	Route::prefix('home')
    		 ->namespace(__NAMESPACE__)
    		 ->group(function ($router)
    	{
    		$class = class_basename(__CLASS__);
    		$router->any('/', $class.'@index');
    	});
    }
    public function index(Request $request)
    {
    	return view('home');
    }
}
