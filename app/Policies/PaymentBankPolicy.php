<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PaymentBank;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentBankPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PaymentBank');
    }

    public function view(AuthUser $authUser, PaymentBank $paymentBank): bool
    {
        return $authUser->can('View:PaymentBank');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PaymentBank');
    }

    public function update(AuthUser $authUser, PaymentBank $paymentBank): bool
    {
        return $authUser->can('Update:PaymentBank');
    }

    public function delete(AuthUser $authUser, PaymentBank $paymentBank): bool
    {
        return $authUser->can('Delete:PaymentBank');
    }

    public function restore(AuthUser $authUser, PaymentBank $paymentBank): bool
    {
        return $authUser->can('Restore:PaymentBank');
    }

    public function forceDelete(AuthUser $authUser, PaymentBank $paymentBank): bool
    {
        return $authUser->can('ForceDelete:PaymentBank');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PaymentBank');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PaymentBank');
    }

    public function replicate(AuthUser $authUser, PaymentBank $paymentBank): bool
    {
        return $authUser->can('Replicate:PaymentBank');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PaymentBank');
    }

}