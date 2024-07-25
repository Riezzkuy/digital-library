<?php

namespace App\Http\Controllers;

use App\Models\Copy;
use Illuminate\Http\Request;

class CopyController extends Controller
{
    public function read(Copy $copy)
    {
        return view('copys.read', [
            'copy' => $copy
        ]);
    }
}
