<?php

namespace App\View\Components\Sidebar;

use Illuminate\View\Component;

class AccountantSidebar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // Pass pending review count if available
        $pendingReviewCount = 0;
        
        // You would typically get this from a service or repository
        // For example: $pendingReviewCount = app(TaskReviewService::class)->getPendingCount();
        
        return view('components.sidebar.accountant', [
            'pendingReviewCount' => $pendingReviewCount
        ]);
    }
}
