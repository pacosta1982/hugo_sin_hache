<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationCenter extends Component
{
    use WithPagination;

    public $showDropdown = false;
    public $isAdmin = false;
    public $employeeId = null;
    
    public function mount($isAdmin = false, $employeeId = null)
    {
        $this->isAdmin = $isAdmin;
        $this->employeeId = $employeeId;
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            if (($this->isAdmin && $notification->empleado_id === null) || 
                (!$this->isAdmin && $notification->empleado_id === $this->employeeId)) {
                $notification->markAsRead();
                $this->dispatch('notificationRead');
            }
        }
    }

    public function markAllAsRead()
    {
        if ($this->isAdmin) {
            Notification::forAdmins()->unread()->update([
                'read' => true,
                'read_at' => now()
            ]);
        } else {
            Notification::forEmployee($this->employeeId)->unread()->update([
                'read' => true,
                'read_at' => now()
            ]);
        }
        $this->dispatch('allNotificationsRead');
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            if (($this->isAdmin && $notification->empleado_id === null) || 
                (!$this->isAdmin && $notification->empleado_id === $this->employeeId)) {
                $notification->delete();
                $this->dispatch('notificationDeleted');
            }
        }
    }

    public function getNotificationsProperty()
    {
        if ($this->isAdmin) {
            return Notification::forAdmins()
                ->latest()
                ->paginate(15);
        } else {
            return Notification::forEmployee($this->employeeId)
                ->latest()
                ->paginate(15);
        }
    }

    public function getUnreadCountProperty()
    {
        if ($this->isAdmin) {
            return Notification::forAdmins()->unread()->count();
        } else {
            return Notification::forEmployee($this->employeeId)->unread()->count();
        }
    }

    public function render()
    {
        return view('livewire.notification-center', [
            'notifications' => $this->notifications,
            'unreadCount' => $this->unreadCount,
        ]);
    }
}
