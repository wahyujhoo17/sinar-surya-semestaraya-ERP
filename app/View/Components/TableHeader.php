<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TableHeader extends Component
{
    public $sortable;
    public $name;
    public $label;

    /**
     * Create a new component instance.
     *
     * @param  bool  $sortable
     * @param  string  $name
     * @param  string  $label
     * @return void
     */
    public function __construct($sortable = false, $name = '', $label = '')
    {
        $this->sortable = $sortable;
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table-header');
    }
}
