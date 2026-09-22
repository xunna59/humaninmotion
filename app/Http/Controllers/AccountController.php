<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Services\Wishlist\WishlistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('account.index', [
            'user' => $user,
            'recentOrders' => $user->orders()
                ->orderByDesc('placed_at')
                ->take(3)
                ->get(),
            'addressCount' => $user->addresses()->count(),
            'wishlistCount' => app(WishlistService::class)->count($user),
            'title' => 'My Account | Human In Motion',
        ]);
    }

    public function orders()
    {
        $orders = Auth::user()->orders()
            ->with('items')
            ->orderByDesc('placed_at')
            ->paginate(10);

        return view('account.orders', [
            'orders' => $orders,
            'title' => 'My Orders | Human In Motion',
        ]);
    }

    public function orderDetails(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(404);
        }

        return view('account.order-details', [
            'order' => $order->load(['items.product', 'shippingAddress', 'billingAddress', 'shipments', 'payments']),
            'title' => 'Order ' . $order->order_number . ' | Human In Motion',
        ]);
    }

    public function addresses()
    {
        return view('account.addresses', [
            'addresses' => Auth::user()->addresses()->orderBy('is_default', 'desc')->get(),
            'title' => 'My Addresses | Human In Motion',
        ]);
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:shipping,billing'],
            'name' => ['required', 'string', 'max:255'],
            'line_one' => ['required', 'string', 'max:255'],
            'line_two' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'county' => ['nullable', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:32'],
        ]);

        $user = Auth::user();
        $isDefault = $request->boolean('is_default') || $user->addresses()->where('type', $data['type'])->count() === 0;

        $address = $user->addresses()->create($data + ['is_default' => $isDefault]);

        if ($isDefault) {
            $user->addresses()
                ->where('id', '!=', $address->id)
                ->where('type', $data['type'])
                ->update(['is_default' => false]);
        }

        return back()->with('success', 'Address saved.');
    }

    public function updateAddress(Request $request, Address $address): RedirectResponse
    {
        abort_if($address->user_id !== Auth::id(), 404);

        $data = $request->validate([
            'type' => ['required', 'in:shipping,billing'],
            'name' => ['required', 'string', 'max:255'],
            'line_one' => ['required', 'string', 'max:255'],
            'line_two' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'county' => ['nullable', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:32'],
        ]);

        $address->update($data);

        return back()->with('success', 'Address updated.');
    }

    public function deleteAddress(Request $request, Address $address): RedirectResponse
    {
        abort_if($address->user_id !== Auth::id(), 404);
        $address->delete();

        return back()->with('success', 'Address removed.');
    }

    public function wishlist()
    {
        $items = app(WishlistService::class)->items(Auth::user());

        return view('account.wishlist', [
            'items' => $items,
            'title' => 'My Wishlist | Human In Motion',
        ]);
    }

    public function removeWishlistItem(\App\Models\Product $product): RedirectResponse
    {
        app(WishlistService::class)->remove($product, Auth::user());

        return back()->with('success', 'Removed from wishlist.');
    }

    public function details()
    {
        return view('account.details', [
            'user' => Auth::user(),
            'title' => 'My Details | Human In Motion',
        ]);
    }

    public function updateDetails(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:32'],
        ]);

        $user->update($data);

        return back()->with('success', 'Details updated.');
    }

    public function password()
    {
        return view('account.password', [
            'title' => 'Change Password | Human In Motion',
        ]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password updated.');
    }
}