<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditPhonesController extends Controller
{
    public function index($phone_id) {
        return view('editPhone',['phone_id' => $phone_id]);
    }
}
