<?php

namespace App\Livewire\Student\AcademicService;

use App\Models\Ulasan;
use App\Models\Submission;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UlasanPKL extends Component
{
    use WithPagination;

    public string $judul = '';
    public string $isi = '';
    public int $rating = 0;
    public bool $showForm = false;
    public bool $showAllUlasan = false;

    public ?Submission $submission = null;
    public ?Ulasan $ulasan = null;
    public bool $canReview = false;

    protected $rules = [
        'judul'  => 'required|string|min:5|max:100',
        'isi'    => 'required|string|min:20|max:2000',
        'rating' => 'required|integer|min:1|max:5',
    ];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData()
    {
        $studentId = Auth::id();
        $this->submission = Submission::where('user_id', $studentId)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if ($this->submission) {
            $this->canReview = $this->submission->finish_date && now()->gte($this->submission->finish_date);
            $this->ulasan = Ulasan::where('submission_id', $this->submission->id)
                ->where('student_id', $studentId)
                ->first();

            if ($this->ulasan) {
                $this->judul = $this->ulasan->judul;
                $this->isi = $this->ulasan->isi;
                $this->rating = $this->ulasan->rating;
            }
        }
    }

    public function openForm()
    {
        $this->dispatch('open-ulasan-modal');
    }

    public function closeForm()
    {
        $this->dispatch('close-ulasan-modal');
        $this->resetValidation();
    }

    public function toggleAllUlasan()
    {
        $this->showAllUlasan = !$this->showAllUlasan;
        if ($this->showAllUlasan) {
            $this->dispatch('open-all-ulasan-modal');
        } else {
            $this->dispatch('close-all-ulasan-modal');
        }
    }

    public function save()
    {
        $this->validate();

        Ulasan::updateOrCreate(
            ['submission_id' => $this->submission->id, 'student_id' => Auth::id()],
            ['judul' => $this->judul, 'isi' => $this->isi, 'rating' => $this->rating]
        );

        $this->loadData();
        $this->dispatch('close-ulasan-modal');
        session()->flash('message', 'Ulasan berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.student.academic-service.ulasan-p-k-l', [
            'previewUlasans' => Ulasan::with(['student', 'submission'])->latest()->take(2)->get(),
            'allUlasans' => $this->showAllUlasan
                ? Ulasan::with(['student', 'submission'])->latest()->paginate(5)
                : collect()
        ]);
    }
}
