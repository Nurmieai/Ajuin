<?php

namespace App\Livewire\Student\AcademicService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UlasanPKL extends Component
{
    public $submission;
    public $ulasan = null;
    
    // Form properties
    public $showForm = false;
    public $judul = '';
    public $isi = '';
    public $rating = 5;

    public function mount()
    {
        // Ambil submission PKL yang sudah approved
        $this->submission = DB::table('submissions')
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->first();
        
        // Ambil ulasan dari session (key berdasarkan submission_id)
        if ($this->submission) {
            $sessionKey = 'ulasan_pkl_' . $this->submission->id . '_' . Auth::id();
            $this->ulasan = Session::get($sessionKey);
        }
    }

    public function openForm()
    {
        $this->showForm = true;
        if ($this->ulasan) {
            $this->judul = $this->ulasan['judul'] ?? '';
            $this->isi = $this->ulasan['isi'] ?? '';
            $this->rating = $this->ulasan['rating'] ?? 5;
        }
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['judul', 'isi', 'rating']);
    }

    public function save()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $sessionKey = 'ulasan_pkl_' . $this->submission->id . '_' . Auth::id();
        
        // Simpan ke session
        $this->ulasan = [
            'judul' => $this->judul,
            'isi' => $this->isi,
            'rating' => $this->rating,
            'created_at' => $this->ulasan['created_at'] ?? now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];
        
        Session::put($sessionKey, $this->ulasan);
        Session::save();

        $this->showForm = false;
        session()->flash('message', 'Ulasan berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.student.academic-service.ulasan-p-k-l');
    }
}