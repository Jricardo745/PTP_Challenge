<?php

namespace App\Policies;

use App\User;
use App\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @return bool
     */
    public function before($user)
    {
        if ($user->hasRole('Admin'))
        {
            return true;
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo('View any invoices')
            || $user->hasPermissionTo('View invoices');
    }

    /**
     * Determine whether the user can view invoices.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return mixed
     */
    public function view(User $user, Invoice $invoice)
    {
        if ($user->hasPermissionTo('View any invoices')) {
            return true;
        } elseif ($user->hasPermissionTo('View invoices')) {
            return $user->id === $invoice->owner_id || $user->id === $invoice->client->user->id;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create invoices.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo('Create invoices');
    }

    /**
     * Determine whether the user can edit invoices.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return mixed
     */
    public function edit(User $user, Invoice $invoice)
    {
        if ($user->hasPermissionTo('Edit any invoices')) {
            return true;
        } elseif ($user->hasPermissionTo('Edit invoices')) {
            return $user->id === $invoice->owner_id;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can annul invoices.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return mixed
     */
    public function delete(User $user, Invoice $invoice)
    {
        if ($user->hasPermissionTo('Annul any invoices')) {
            return true;
        } elseif ($user->hasPermissionTo('Annul invoices')) {
            return $user->id === $invoice->owner_id;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can export invoices.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return mixed
     */
    public function export(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo('Export invoices');
    }

    /**
     * Determine whether the user can import invoices.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return mixed
     */
    public function import(User $user, Invoice $invoice)
    {
        if ($user->hasPermissionTo('Import any invoices')) {
            return true;
        } elseif ($user->hasPermissionTo('Import invoices')) {
            return $user->id === $invoice->owner_id;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can pay his invoices.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return mixed
     */
    public function pay(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo('Pay invoices')
            && $user->id === $invoice->client->user->id;
    }

    /**
     * Determine whether the user can receive his invoices.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return mixed
     */
    public function receive(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo('Receive invoices')
            && $user->id === $invoice->client->user->id;
    }
}