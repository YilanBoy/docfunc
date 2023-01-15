<?php

namespace App\Http\Livewire\Users\Information;

use App\Models\Category;
use App\Models\User;
use Livewire\Component;

class PersonalInformation extends Component
{
    public string $userId;

    public function render()
    {
        $user = User::find($this->userId)
            ->loadCount(['posts as posts_count_in_this_year' => function ($query) {
                $query->whereYear('created_at', date('Y'));
            }]);

        $categories = Category::with(['posts' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        return view('livewire.users.information.personal-information', compact('user', 'categories'));
    }
}
