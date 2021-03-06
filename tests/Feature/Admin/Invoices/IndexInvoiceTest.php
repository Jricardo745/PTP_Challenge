<?php

namespace Tests\Feature\Admin\Invoices;

use Carbon\Carbon;
use Tests\TestCase;
use App\Entities\User;
use App\Entities\Product;
use App\Entities\Invoice;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class IndexInvoiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_access_to_invoices_index()
    {
        $this->get(route('invoices.index'))->assertRedirect(route('login'));
    }

    /** @test */
    public function unauthorized_user_cannot_access_to_invoices_index()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->get(route('invoices.index'))->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_access_to_invoices_index()
    {
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);

        $response = $this->actingAs($user)->get(route('invoices.index'));
        $response->assertOk();
        $response->assertViewIs("invoices.index");
        $response->assertSee("Facturas");
    }

    /** @test */
    public function the_index_of_invoices_has_invoices()
    {
        factory(Invoice::class, 5)->create();
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);

        $this->actingAs($user)->get(route('invoices.index'))
            ->assertViewHas('invoices');
    }

    /** @test */
    public function the_index_of_invoices_has_invoice_paginated()
    {
        factory(Invoice::class, 5)->create();
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);

        $response = $this->actingAs($user)->get(route('invoices.index'));
        $this->assertInstanceOf(
            LengthAwarePaginator::class,
            $response->original->gatherData()['invoices']
        );
    }

    /** @test */
    public function display_message_to_the_user_when_no_invoices_where_found()
    {
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);

        $response = $this->actingAs($user)->get(route('invoices.index'));
        $response->assertSee(__('No se encontraron facturas'));
    }

    /** @test */
    public function invoices_can_be_found_by_issued_init_and_issued_final()
    {
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);
        $invoice1 = factory(Invoice::class)->create(['issued_at' => Carbon::now()->subWeek()]);
        $invoice2 = factory(Invoice::class)->create(['issued_at' => Carbon::now()->addWeek()]);
        $invoice3 = factory(Invoice::class)->create(['issued_at' => Carbon::now()]);

        $response = $this->actingAs($user)->get(route('invoices.index'));
        $response->assertSeeText($invoice1->fullname);
        $response->assertSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);

        $response = $this->actingAs($user)->get(route('invoices.index', [
            'issued_init' => Carbon::now()->toDateString(),
            'issued_final' => Carbon::now()->toDateString(),
        ]));
        $response->assertDontSeeText($invoice1->fullname);
        $response->assertDontSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);
    }

    /** @test */
    public function invoices_can_be_found_by_expires_init_and_expires_final()
    {
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);
        $invoice1 = factory(Invoice::class)->create(['issued_at' => Carbon::now()->subWeek()]);
        $invoice2 = factory(Invoice::class)->create(['issued_at' => Carbon::now()->addWeek()]);
        $invoice3 = factory(Invoice::class)->create(['issued_at' => Carbon::now()]);

        $response = $this->actingAs($user)->get(route('invoices.index'));
        $response->assertSeeText($invoice1->fullname);
        $response->assertSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);

        $response = $this->actingAs($user)->get(route('invoices.index', [
            'expires_init' => Carbon::now()->addMonth()->toDateString(),
            'expires_final' => Carbon::now()->addMonth()->toDateString(),
        ]));
        $response->assertDontSeeText($invoice1->fullname);
        $response->assertDontSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);
    }

    /** @test */
    public function invoices_can_be_found_by_number()
    {
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);
        $invoice1 = factory(Invoice::class)->create();
        $invoice2 = factory(Invoice::class)->create();
        $invoice3 = factory(Invoice::class)->create();

        $response = $this->actingAs($user)->get(route('invoices.index'));
        $response->assertSeeText($invoice1->fullname);
        $response->assertSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);

        $response = $this->actingAs($user)->get(route('invoices.index', ['number' => $invoice3->id]));
        $response->assertDontSeeText($invoice1->fullname);
        $response->assertDontSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);
    }

    /** @test */
    public function invoices_can_be_found_by_product()
    {
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);
        $invoices = factory(Invoice::class, 5)->create()->each(function ($invoice) {
            $product = factory(Product::class)->create();
            $invoice->products()->attach($product->id, ['quantity' => 1, 'unit_price' => 1]);
        });

        $response = $this->actingAs($user)->get(route('invoices.index'));
        foreach ($invoices as $invoice) {
            $response->assertSeeText($invoice->fullname);
        }

        $response = $this->actingAs($user)->get(route('invoices.index', ['product_id' => $invoices->last()->products->first()->id]));
        foreach ($invoices as $invoice) {
            if ($invoice !== $invoices->last()) {
                $response->assertDontSeeText($invoice->fullname);
            } else {
                $response->assertSeeText($invoice->fullname);
            }
        }
    }

    /** @test */
    public function invoices_can_be_found_by_client()
    {
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);
        $invoice1 = factory(Invoice::class)->create();
        $invoice2 = factory(Invoice::class)->create();
        $invoice3 = factory(Invoice::class)->create();

        $response = $this->actingAs($user)->get(route('invoices.index'));
        $response->assertSeeText($invoice1->fullname);
        $response->assertSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);

        $response = $this->actingAs($user)->get(route('invoices.index', ['client_id' => $invoice3->client_id]));
        $response->assertDontSeeText($invoice1->fullname);
        $response->assertDontSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);
    }

    /** @test */
    public function invoices_can_be_found_by_seller()
    {
        $permission = Permission::create(['name' => 'View all invoices']);
        $user = factory(User::class)->create()->givePermissionTo($permission);
        $invoice1 = factory(Invoice::class)->create();
        $invoice2 = factory(Invoice::class)->create();
        $invoice3 = factory(Invoice::class)->create();

        $response = $this->actingAs($user)->get(route('invoices.index'));
        $response->assertSeeText($invoice1->fullname);
        $response->assertSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);

        $response = $this->actingAs($user)->get(route('invoices.index', ['created_by' => $invoice3->created_by]));
        $response->assertDontSeeText($invoice1->fullname);
        $response->assertDontSeeText($invoice2->fullname);
        $response->assertSeeText($invoice3->fullname);
    }
}
