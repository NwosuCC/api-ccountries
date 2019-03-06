<?php

namespace App\Http\Controllers;

use App\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
      $audit = Audit::all();

      return response()->json($audit, 200);
    }
}
