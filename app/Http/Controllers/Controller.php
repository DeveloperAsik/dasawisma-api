<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
//load custom libraries class
use App\Http\Libraries\Variables_Library AS VLibrary;

use View;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function __construct(Request $request) {
        $this->initVar($request);
        $this->initAuth($request);
    }

    public function initVar($request) {
        $conf = VLibrary::init();
        if ($conf['PATH']) {
            foreach ($conf['PATH'] AS $key => $values) {
                /*
                 * enable actual config variable to load globally 
                 * start here
                 */
                View::share($key, $values);
                /*
                 * enable actual config variable to load globally 
                 * end here
                 */
                $this->{$key} = $values;
            }
        }

        if ($conf['CONFIG']) {
            foreach ($conf['CONFIG'] AS $key => $values) {
                /*
                 * enable actual config variable to load globally 
                 * start here
                 */
                View::share($key, $values);
                /*
                 * enable actual config variable to load globally 
                 * end here
                 */
                $this->{$key} = $values;
            }
        }
    }

    public function initAuth($request) {
        if ($request->input('token') || $request->header('token')) {
            $token = ($request->input('token')) ? $request->input('token') : $request->header('token');
            $user_token = DB::table('tbl_user_tokens AS a')->select('a.*')->where('a.is_active', 1)->Where('a.token_generated', 'like', '%' . $token . '%')->get();
            if (isset($user_token[0]->token_generated) && !empty($user_token[0]->token_generated)) {
                $this->user_token = new \stdClass();
                foreach ($user_token[0] AS $key => $values) {
                    $this->user_token->{$key} = $values;
                }
                return json_encode(array('status' => 200, 'message' => 'your token is valid', 'data' => array('valid' => true)));
            } else {
                return json_encode(array('status' => 200, 'message' => 'your token is not valid', 'data' => array('valid' => false)));
            }
        }
    }

}
