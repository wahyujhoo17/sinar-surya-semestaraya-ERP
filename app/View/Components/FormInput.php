<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    public $label;
    public $name;
    public $type;
    public $value;
    public $required;
    
    public function __construct($label, $name, $type = 'text', $value = null, $required = false)
    {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->required = $required;
    }
    
    public function render()
    {
        return view('components.form-input');
    }
}