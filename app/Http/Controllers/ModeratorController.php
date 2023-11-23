<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ModeratorController extends Controller
{
    public function moderatorPage()
    {
      return view('moderator');
    }
}
