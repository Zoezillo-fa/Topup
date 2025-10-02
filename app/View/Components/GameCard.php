<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Game; // <-- Impor model Game

class GameCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Game $game // <-- Terima data game
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure
    {
        return view('components.game-card');
    }
}