<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class NewsletterSubscriptionController extends Controller
{
    /**
     * Authorize action or abort with 403.
     */
    private function authorizeNewsletterPermission(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }

    /**
     * Display a listing of the newsletter subscriptions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorizeNewsletterPermission('view_newsletter_subscriptions');

        $query = NewsletterSubscription::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('email', 'like', "%{$search}%");
        }

        $subscriptions = $query->latest()
                               ->paginate(15)
                               ->withQueryString();

        $totalCount = NewsletterSubscription::count();

        return view('newsletters.index', compact('subscriptions', 'totalCount'));
    }

    /**
     * Remove the specified newsletter subscription from storage.
     *
     * @param  \App\Models\NewsletterSubscription  $newsletterSubscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->authorizeNewsletterPermission('delete_newsletter_subscriptions');

        $subscription = NewsletterSubscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('newsletter-subscriptions.index')
                         ->with('success', 'Subscriber removed successfully.');
    }

    /**
     * Export all newsletter subscriptions to CSV.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        $this->authorizeNewsletterPermission('view_newsletter_subscriptions');

        $fileName = 'newsletter_subscribers_' . date('Y-m-d') . '.csv';
        $subscribers = NewsletterSubscription::orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Email', 'Subscribed At']);

            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->id,
                    $subscriber->email,
                    $subscriber->created_at ? $subscriber->created_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
