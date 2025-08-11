<?php

namespace App\Livewire\Admin;

use App\Services\RealtimeService;
use Livewire\Component;

class RealtimeStatus extends Component
{
    public $realtimeConfig = [];
    public $testResult = null;
    public $activeConnections = [];
    
    public function mount()
    {
        $this->loadRealtimeConfig();
        $this->loadActiveConnections();
    }

    public function loadRealtimeConfig()
    {
        $realtimeService = app(RealtimeService::class);
        $this->realtimeConfig = $realtimeService->getRealtimeConfig();
    }

    public function loadActiveConnections()
    {
        $realtimeService = app(RealtimeService::class);
        $this->activeConnections = $realtimeService->getActiveConnections();
    }

    public function testRealtimeConnection()
    {
        $realtimeService = app(RealtimeService::class);
        $this->testResult = $realtimeService->testRealtimeConnection();
        
        $this->dispatch('realtimeTestCompleted', $this->testResult);
    }

    public function refreshStatus()
    {
        $this->loadRealtimeConfig();
        $this->loadActiveConnections();
        $this->testResult = null;
        $this->dispatch('success', 'Estado actualizado');
    }

    public function render()
    {
        return view('livewire.admin.realtime-status');
    }
}
