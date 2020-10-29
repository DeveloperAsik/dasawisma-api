<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
//load laravel feature
use View;
//load custom libraries class
use App\Http\Libraries\Variables_Library AS VLibrary;
use App\Http\Libraries\Session_Library AS SesLibrary;
use App\Http\Libraries\Auth AS AuthLibrary;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function __construct(Request $request) {
        $this->initVar();
        $this->initAuth();

        if ($request->input('token')) {
            $user_token = DB::table('tbl_user_tokens AS a')->select('a.*')->where('a.is_active', 1)->Where('a.token_generated', 'like', '%' . $request->input('token') . '%')->get();
            if (isset($user_token[0]->token_generated) && !empty($user_token[0]->token_generated)) {
                return json_encode(array('status' => 200, 'message' => 'your token is valid', 'data' => array('valid' => true)));
            } else {
                return json_encode(array('status' => 200, 'message' => 'your token is not valid', 'data' => array('valid' => false)));
            }
        }
    }

    public function initVar() {
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

    public function initAuth() {
        if (!SesLibrary::_get('_uuid') || SesLibrary::_get('_uuid') == null) {
            SesLibrary::_set('_uuid', uniqid());
        }
        if (SesLibrary::_get('_is_logged_in')) {
            View::share('_is_logged_in', SesLibrary::_get('_is_logged_in'));
        }
        if (SesLibrary::_get('_token')) {
            View::share('_token', SesLibrary::_get('_token'));
        }
        AuthLibrary::verify_group_permission(\Request::route()->getName());
    }

}
