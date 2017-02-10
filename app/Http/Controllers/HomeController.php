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
            $router->any('/skeleton', $class.'@skeleton_rest');
            $router->any('/bukasne/{kjob}/{syozok}', $class.'@bukasne_rest');
            $router->any('/tokusne/{kjob}/{syozok}/{tokuno?}', $class.'@tokusne_rest');

    	});
    }
    public function __call($method, $arguments)
    {
        if (preg_match('/^(.+)_rest$/', $method, $matches))
        {
            return $this->{$matches[1]}(...$arguments);
        }
    }
    public function index(Request $request)
    {
    	return view('home');
    }
    public function skeleton()
    {
        return collect([

            ['syozok'=>170, 'bukame'=>'水産１課'], 
            ['syozok'=>150, 'bukame'=>'水産２課'], 
            ['syozok'=>131, 'bukame'=>'水産３課'], 
            ['syozok'=>141, 'bukame'=>'水産４課'], 
            ['syozok'=>160, 'bukame'=>'日配１課'], 
            ['syozok'=>134, 'bukame'=>'日配２課'], 
            ['syozok'=>161, 'bukame'=>'日配３課'], 
            ['syozok'=>610, 'bukame'=>'東日本水産'], 
            ['syozok'=>620, 'bukame'=>'東日本日配'], 
            ['syozok'=>710, 'bukame'=>'山陰量販'], 
            ['syozok'=>830, 'bukame'=>'中部水産'], 
            ['syozok'=>910, 'bukame'=>'西日本水産１課'], 
            ['syozok'=>920, 'bukame'=>'西日本水産２課'], 
            ['syozok'=>930, 'bukame'=>'西日本テナント'], 
        
        ])->map(function ($a) { return (object)$a; });
    }
    public function bukasne($kjob, $syozok)
    {
        dump(compact('kjob', 'syozok', 'tokuno'));
    }
    public function tokusne($kjob, $syozok, $tokuno = null)
    {
        dump(compact('kjob', 'syozok', 'tokuno'));
    }
}
