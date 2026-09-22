<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::query()->with(['product:id,name,slug', 'user:id,name,email']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.reviews.index', [
            'reviews' => $query->orderByDesc('created_at')->paginate(25)->withQueryString(),
            'title' => 'Reviews',
        ]);
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $review->update($data);
        AuditLog::record('admin.review.updated', $review, $data);

        return back()->with('status', 'Review marked as ' . $data['status'] . '.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        AuditLog::record('admin.review.deleted', null, ['id' => $review->id]);

        return back()->with('status', 'Review deleted.');
    }
}