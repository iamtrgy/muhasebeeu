<?php $user = \App\Models ser::where('email', 'hi@turgay.org')->first(); var_dump(['is_admin' => $user->is_admin, 'is_accountant' => $user->is_accountant, 'id' => $user->id]);
