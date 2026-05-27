<?php
namespace App\Policies;
use App\Models\Registration; use App\Models\User;
class RegistrationPolicy { public function before(User $user): ?bool { return $user->is_admin ? true : null; } public function viewAny(User $user): bool { return true; } public function view(User $user, Registration $registration): bool { return $registration->user_id === $user->id; } public function update(User $user, Registration $registration): bool { return $registration->user_id === $user->id; } }
