<?php

namespace App\Policies;

use App\Models\PaymentProof;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentProofPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Finance Admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentProof $paymentProof): bool
    {
        if ($user->hasAnyRole(['Super Admin', 'Finance Admin'])) {
            return true;
        }

        return $paymentProof->order->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentProof $paymentProof): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Finance Admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentProof $paymentProof): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Finance Admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentProof $paymentProof): bool
    {
        return $user->hasAnyRole(['Super Admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentProof $paymentProof): bool
    {
        return $user->hasAnyRole(['Super Admin']);
    }
}
