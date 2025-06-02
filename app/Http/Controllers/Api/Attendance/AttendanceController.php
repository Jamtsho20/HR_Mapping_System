<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use PDO;

class AttendanceController extends Controller
{
    use JsonResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){

    }

    public function store(Request $request){

    }

    public function show($id){

    }

    public function update(Request $request){

    }

    public function destroy()
    {
        
    }
}
