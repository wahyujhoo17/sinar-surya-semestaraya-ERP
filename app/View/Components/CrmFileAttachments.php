<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CrmFileAttachments extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $existingAttachments = [],
        public string $modelType = 'prospek',
        public ?int $modelId = null
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.crm-file-attachments');
    }
}