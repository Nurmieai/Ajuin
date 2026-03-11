<?php

namespace App\Livewire\Student\AcademicService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        
        // Ambil ulasan dari database
        if ($this->submission) {
            $ulasan = DB::table('ulasan_pkl')
                ->where('user_id', Auth::id())
                ->where('submission_id', $this->submission->id)
                ->first();

            if ($ulasan) {
                $this->ulasan = (array) $ulasan;
            }
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

        $data = [
            'judul'         => $this->judul,
            'isi'           => $this->isi,
            'rating'        => $this->rating,
            'updated_at'    => now(),
        ];

        $existing = DB::table('ulasan_pkl')
            ->where('user_id', Auth::id())
            ->where('submission_id', $this->submission->id)
            ->first();

        if ($existing) {
            // Update ulasan yang sudah ada
            DB::table('ulasan_pkl')
                ->where('id', $existing->id)
                ->update($data);
        } else {
            // Insert ulasan baru
            DB::table('ulasan_pkl')->insert(array_merge($data, [
                'user_id'       => Auth::id(),
                'submission_id' => $this->submission->id,
                'created_at'    => now(),
            ]));
        }

        // Refresh ulasan dari database
        $this->ulasan = (array) DB::table('ulasan_pkl')
            ->where('user_id', Auth::id())
            ->where('submission_id', $this->submission->id)
            ->first();

        $this->showForm = false;
        session()->flash('message', 'Ulasan berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.student.academic-service.ulasan-p-k-l');
    }
}