<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CounterCard extends Component
{
    public $title;

    public $count;

    public $icon;

    public $bgColor;

    /**
     * Create a new component instance.
     */
    public function __construct($title, $count, $icon, $bgColor)
    {
        $this->title = $title;
        $this->count = $count;
        $this->icon = $icon;
        $this->bgColor = $bgColor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.counter-card');
    }
}
