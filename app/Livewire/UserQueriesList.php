<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserQuery;
use Illuminate\Support\Facades\Auth;

class UserQueriesList extends Component
{
    public function render()
    {
        $queries = UserQuery::with('user')->latest()->paginate(20);
        return view('livewire.user-queries-list', compact('queries'));
    }
}
