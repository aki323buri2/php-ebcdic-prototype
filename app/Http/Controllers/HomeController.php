<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Route;
use DB;
use Carbon\Carbon;

use DeepCopy\DeepCopy;

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
    public function prepare($kjob, $syozok)
    {
        $kjob = Carbon::parse($kjob)->format('Y-m-d');
        $suffix = $this->suffix($syozok);
        $connection = $this->connection();
        return compact('kjob', 'suffix', 'connection');
    }
    public function suffix($syozok)
    {
        switch ($syozok)
        {
            case 610: 
            case 620: 
                return '610'; 
            case 710: 
                return '710'; 
            case 910: 
            case 920: 
            case 930: 
                return '910'; 
            default: 
                return '';
        }
    }
    public function connection()
    {
        return DB::connection();
    }
    public function bukasne($kjob, $syozok)
    {
        dump($this->prepare($kjob, $syozok));
    }
    public function tokusne($kjob, $syozok, $tokuno = null)
    {
        dump($this->prepare($kjob, $syozok));

        $fdg = storage_path('URIRUISF.FDG.txt');
        $ebc = storage_path('URIRUISF.161');

        $fdg = storage_path('TMASAPF.RDMLIB.FDG.txt');
        $ebc = storage_path('TMASAPF.RDMLIB');

        $columns = $this->parse_fdg($fdg);

        $guide = $this->expand2guide($columns);

        $rlen = collect($guide)->sum('bytes');
        dump($rlen);

        $utf8 = $this->ftran($ebc, $guide);
    }
    public function parse_fdg($path)
    {
        $file = file_get_contents($path);
        $lines = collect(explode("\r\n", $file));

        $pattern = '/'
            . '^\s*'
            . '(\d+)\s+'
            . '([^\s.]+)'
            . '(?:\s+'
                . 'PIC\s+'
                . '(S)?'
                . '(9|X|N)\s*'
                . '[(]\s*(\d+)\s*[)]'
                . '(?:(?:V9)?[(]\s*(\d+)\s*[)])?'
                . '(?:\s+(PACKED-DECIMAL|COMP-3))?'
            . ')?'
            . '(?:\s+'
                . 'OCCURS\s+(\d+)'
            . ')?'
            . '[.]?'
            . '/';
        $fields = [

            'lv', 
            'name', 
            'sig', 
            'type', 
            'left', 
            'right', 
            'pack', 
            'occurs', 
            'bytes', 
        ];
        $columns = $lines->map(function ($s) use ($pattern, $fields)
        {
            if (preg_match($pattern, $s, $matches))
            {
                $i = 0;
                $i++;
                $lv    = (int)@$matches[$i++];
                $name  = @$matches[$i++];
                $sig   = @$matches[$i++] === 'S';
                $type  = @$matches[$i++];
                $left  = (int)@$matches[$i++];
                $right = (int)@$matches[$i++];
                $pack  = !is_null(@$matches[$i++]);
                $occurs = (int)@$matches[$i++];

                // dump(compact(...$fields));
                $bytes = $this->compute_bytes($left + $right, $type, $pack);
                
                return (object)compact(...$fields);
            }

        })->filter(function ($item) { return !is_null($item); });

        return $columns;
    }
    public function expand2guide($columns)
    {
        $guide = [];

        $memo = null;
        $stock = [];

        $deepcopy = new DeepCopy();

        foreach ($columns as $column)
        {
            
            if ($column->occurs > 0)
            {
                if (!$column->type)
                {
                    $memo = $deepcopy->copy($column);
                    $stock = [];
                }
            }
            else if (!is_null($memo) && $memo->occurs > 0)
            {
                if ($column->lv > $memo->lv)
                {
                    $stock[] = $deepcopy->copy($column);
                }
                else
                {
                    for ($i = 0; $i < $memo->occurs; $i++)
                    {
                        foreach ($stock as $pick)
                        {
                            $copy = $deepcopy->copy($pick);
                            $copy->index = $i;
                            $guide[] = $copy;
                        }
                    }
                    $memo = null;
                    $stock = [];
                    dump($column->name);
                }
            }

            if (count($stock) === 0)
            {
                $guide[] = $deepcopy->copy($column);
            }
        }

        return $guide;
    }
    public function compute_bytes($size, $type, $pack)
    {
        $bytes = $size;
        if ($bytes === 0) return 0;
        if ($pack)
        {
            if ($bytes % 2)
            {
                $bytes++;
            }
            else 
            {
                $bytes += 2;
            }
            $bytes /= 2;
        }
        else if ($type === 'N')
        {
            $bytes *= 2;
        }
        return $bytes;
    }
    public function ftran($path, $guide)
    {
        $handle = fopen($path, 'rb');

        $rlen = collect($guide)->sum('bytes');
        
        foreach (range(1, 10) as $no) fseek($handle, $rlen, SEEK_CUR);
        
        foreach ($guide as $column)
        {
            extract((array)$column);

            if ($column->bytes === 0) continue;

            $read = bin2hex(fread($handle, $column->bytes));

            $utf8 = $pack ? $read : (
                $column->type === 'N' 
                ? $this->jef2utf8($read)
                : $this->ebc2utf8($read)
            );
            dump(compact('name', 'index', 'type', 'left', 'right', 'pack', 'bytes', 'read', 'utf8'));

            unset($lv, $name, $index, $sig, $type, $left, $right, $pack, $occurs);
        }
    }

    public function ebc2utf8($ebc)
    {
        $hash = [
            /*    0     1     2     3     4     5     6     7     8     9     A     B     C     D     E     F   */
            /*0*/0x00, 0x01, 0x02, 0x03, 0x20, 0x09, 0x20, 0x7F, 0x20, 0x20, 0x20, 0x0B, 0x0C, 0x0D, 0x0E, 0x0F, 
            /*1*/0x10, 0x11, 0x12, 0x13, 0x20, 0x0A, 0x08, 0x20, 0x18, 0x19, 0x20, 0x20, 0x20, 0x1D, 0x1E, 0x1F, 
            /*2*/0x20, 0x20, 0x1C, 0x20, 0x20, 0x0A, 0x17, 0x1B, 0x20, 0x20, 0x20, 0x20, 0x20, 0x05, 0x06, 0x07, 
            /*3*/0x20, 0x20, 0x16, 0x20, 0x20, 0x20, 0x20, 0x04, 0x20, 0x20, 0x20, 0x20, 0x14, 0x15, 0x20, 0x1A, 
            /*4*/0x20, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0x5B, 0x2E, 0x3C, 0x28, 0x2B, 0x21, 
            /*5*/0x26, 0xAA, 0xAB, 0xAC, 0xAD, 0xAE, 0xAF, 0x20, 0xB0, 0x20, 0x5D, 0x5C, 0x2A, 0x29, 0x3B, 0x5E, 
            /*6*/0x2D, 0x2F, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x7C, 0x2C, 0x25, 0x5F, 0x3E, 0x3F, 
            /*7*/0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x60, 0x3A, 0x23, 0x40, 0x27, 0x3D, 0x22, 
            /*8*/0x20, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xBA, 0x20, 0xBB, 0xBC, 0xBD, 0xBE, 
            /*9*/0xBF, 0xC0, 0xC1, 0xC2, 0xC3, 0xC4, 0xC5, 0xC6, 0xC7, 0xC8, 0xC9, 0x20, 0x20, 0xCA, 0xCB, 0xCC, 
            /*A*/0x20, 0x7E, 0xCD, 0xCE, 0xCF, 0xD0, 0xD1, 0xD2, 0xD3, 0xD4, 0xD5, 0x20, 0xD6, 0xD7, 0xD8, 0xD9, 
            /*B*/0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 0xDA, 0xDB, 0xDC, 0xDD, 0xDE, 0xDF, 
            /*C*/0x7B, 0x41, 0x42, 0x43, 0x44, 0x45, 0x46, 0x47, 0x48, 0x49, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 
            /*D*/0x7D, 0x4A, 0x4B, 0x4C, 0x4D, 0x4E, 0x4F, 0x50, 0x51, 0x52, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 
            /*E*/0x24, 0x20, 0x53, 0x54, 0x55, 0x56, 0x57, 0x58, 0x59, 0x5A, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 
            /*F*/0x30, 0x31, 0x32, 0x33, 0x34, 0x35, 0x36, 0x37, 0x38, 0x39, 0x20, 0x20, 0x20, 0x20, 0x20, 0x20, 
        ];

        $utf8 = '';
        foreach (str_split($ebc, 2) as $hex)
        {
            $dec = hexdec($hex);
            $dec = @$hash[$dec];
            $utf8 .= mb_convert_encoding(chr($dec), 'UTF-8', 'CP932');
        }

        return $utf8;
    }
    public function jef2utf8($jef)
    {
        $utf8 = '';
        foreach (str_split($jef, 4) as $hex)
        {
            $dec = hexdec($hex);
            $dec -= 0x8080;
            $dec &= 0xffff;
            $hex = dechex($dec);
            $utf8 .= $this->jis($hex);
        }
        return $utf8;
    }

    public function __construct()
    {
        $read = file_get_contents(storage_path('jisjis.txt'));
        $read = collect(explode("\n", $read))->map(function ($line)
        {
            return collect(explode("\t", $line));
        });
        $offset = $this->offset = (object)[];
        $offset->lo = hexdec($read[0][1]) - 1;
        $offset->hi = hexdec($read[1][0]) - 1;

        $this->jisjis = $read->slice(1)->map(function ($a)
        {
            return $a->slice(1);
        });
    }
    public function jis($hex)
    {
        $offset = $this->offset;
        $jisjis = $this->jisjis;
        $dec = hexdec($hex);
        $lo = $dec / 0x100;
        $hi = $dec % 0x100;

        return @$jisjis[$lo - $offset->lo][$hi - $offset->hi];
    }
}
