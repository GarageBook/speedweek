<?php
namespace App\Policies;
use App\Models\User;
class UserPolicy { public function before(User $user): ?bool { return $user->is_admin ? true : null; } public function viewAny(User $user): bool { return false; } public function view(User $user, User $model): bool { return $user->id === $model->id; } }
