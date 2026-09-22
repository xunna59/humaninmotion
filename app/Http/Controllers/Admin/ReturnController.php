<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ReturnRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(): View
    {
        return view('admin.returns.index', [
            'returns' => ReturnRequest::query()
                ->with(['order:id,order_number,total', 'user:id,name,email'])
                ->orderByDesc('created_at')
                ->get(),
            'title' => 'Returns',
        ]);
    }

    public function show(ReturnRequest $returnRequest): View
    {
        $returnRequest->load(['items.orderItem.product:id,name', 'order', 'user']);

        return view('admin.returns.show', [
            'returnRequest' => $returnRequest,
            'title' => 'Return ' . ($returnRequest->number ?: '#' . $returnRequest->id),
        ]);
    }

    public function update(Request $request, ReturnRequest $returnRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:requested,approved,rejected,received,inspected,refunded,completed'],
            'resolution' => ['nullable', 'string', 'max:2000'],
        ]);

        $returnRequest->update($data);
        AuditLog::record('admin.return.updated', $returnRequest, $data);

        return back()->with('status', 'Return updated.');
    }
}