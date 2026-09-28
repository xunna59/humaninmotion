<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::query()->with(['order:id,order_number,customer_email,user_id']);

        if ($term = trim($request->string('q')->toString())) {
            $query->where(function ($q) use ($term) {
                $q->where('transaction_id', 'like', "%{$term}%")
                    ->orWhere('intent_id', 'like', "%{$term}%")
                    ->orWhereHas('order', function ($o) use ($term) {
                        $o->where('order_number', 'like', "%{$term}%")
                            ->orWhere('customer_email', 'like', "%{$term}%");
                    });
            });
        }

        if ($request->filled('provider')) {
            $query->where('provider', $request->input('provider'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.payments.index', [
            'payments' => $query->orderByDesc('created_at')->paginate(25)->withQueryString(),
            'providers' => Payment::query()->distinct()->orderBy('provider')->pluck('provider'),
            'statuses' => [
                Payment::STATUS_PENDING,
                Payment::STATUS_SUCCEEDED,
                Payment::STATUS_CAPTURED,
                Payment::STATUS_FAILED,
                Payment::STATUS_REFUNDED,
            ],
            'title' => 'Payments',
        ]);
    }
}
