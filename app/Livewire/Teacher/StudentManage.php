<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class StudentManage extends Component
{
    public $selectedStudent = null;
    public $showDetailModal = false;
    public $activeTab = 'active';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function showDetail($studentId)
    {
        $this->selectedStudent = User::withTrashed()
            ->with(['major', 'submissions.certificates'])
            ->students()
            ->findOrFail($studentId);
        
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->reset('selectedStudent');
    }

    public function confirmDeactivate($studentId)
    {
        $this->selectedStudent = User::students()
            ->where('is_active', true)
            ->findOrFail($studentId);
        
        $this->dispatch('open-deactivate-modal');
    }

    public function confirmDelete($studentId)
    {
        $this->selectedStudent = User::students()
            ->findOrFail($studentId);
        
        $this->dispatch('open-delete-modal');
    }

    public function confirmRestore($studentId)
    {
        $this->selectedStudent = User::onlyTrashed()
            ->students()
            ->findOrFail($studentId);
        
        $this->dispatch('open-restore-modal');
    }

    public function deactivate()
    {
        if (!$this->selectedStudent) {
            return;
        }

        try {
            $this->selectedStudent->update([
                'is_active' => false
            ]);

            $this->reset('selectedStudent');
            $this->dispatch('close-deactivate-modal');
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ');
        }
    }


    public function delete()
    {
        if (!$this->selectedStudent) {
            return;
        }

        try {
            $this->selectedStudent->delete();

            $this->reset('selectedStudent');
            $this->dispatch('close-delete-modal');
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ');
        }
    }

    public function restore()
    {
        if (!$this->selectedStudent) {
            return;
        }

        try {
            $this->selectedStudent->restore();

            $this->reset('selectedStudent');
            $this->dispatch('close-restore-modal');
            
            session()->flash('success', 'Akun siswa berhasil dipulihkan dari arsip');
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ');
        }
    }

    public function render()
    {
        $students = match($this->activeTab) {
            'active' => User::students()
                ->active()
                ->with('major')
                ->latest()
                ->get(),
            'inactive' => User::students()
                ->inactive()
                ->with('major')
                ->latest()
                ->get(),
            'archived' => User::onlyTrashed()
                ->students()
                ->with('major')
                ->latest()
                ->get(),
            default => collect([])
        };

        return view('livewire.teacher.student-manage', [
            'students' => $students
        ]);
    }
}