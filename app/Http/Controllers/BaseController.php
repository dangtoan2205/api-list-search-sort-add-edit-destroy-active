<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const LOGIN = 'login';
    const INDEX = 'index';
    const LIST = 'list';
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';


    const DUPLICATE_EMAIL = 'Duplicate email';
}
