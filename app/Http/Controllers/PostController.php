<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Mail;
use App;
use PDF;
use PdfMerger;
use Response;
use Log;
use Excel;
use Storage;
use Config;

use App\Http\Requests;
use App\Helpers\Helper;
use App\ProblemType;
use App\WorkOrder;
use App\Tenant;
use App\Property;
use App\User;
use App\Owner;
use App\ChargeCode;
use App\Post;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function newPost($id, Request $request)
    {
    	$post = new Post;
        $post->user_id = Auth::user()->id;
        $post->message = $request->post_message;
        $post->work_order_id = $id;
        $post->save();

        return $post;
    }


}