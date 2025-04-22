<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UnifiedHeader extends Component
{
    /**
     * The breadcrumbs for the header.
     *
     * @var array
     */
    public $breadcrumbs;

    /**
     * The title for the header.
     *
     * @var string|null
     */
    public $title;

    /**
     * The description for the header.
     *
     * @var string|null
     */
    public $description;

    /**
     * Create a new component instance.
     *
     * @param array $breadcrumbs
     * @param string|null $title
     * @param string|null $description
     * @return void
     */
    public function __construct($breadcrumbs = [], $title = null, $description = null)
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.unified-header');
    }
}
