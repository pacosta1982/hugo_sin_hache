<?php

namespace App\Livewire\Admin;

use App\Services\EmailService;
use Livewire\Component;

class EmailSettings extends Component
{
    public $testEmail = '';
    public $testResult = null;
    public $emailMetrics = [];
    
    protected $rules = [
        'testEmail' => 'required|email',
    ];

    public function mount()
    {
        $this->loadEmailMetrics();
        $this->testEmail = auth()->user()->email ?? 'test@example.com';
    }

    public function loadEmailMetrics()
    {
        $emailService = app(EmailService::class);
        $this->emailMetrics = $emailService->getEmailMetrics();
    }

    public function testEmailConfiguration()
    {
        $this->validate();
        
        $emailService = app(EmailService::class);
        $this->testResult = $emailService->testEmailConfiguration();
        
        $this->dispatch('emailTestCompleted', $this->testResult);
    }

    public function refreshMetrics()
    {
        $this->loadEmailMetrics();
        $this->dispatch('success', 'Métricas actualizadas');
    }

    public function render()
    {
        return view('livewire.admin.email-settings');
    }
}
