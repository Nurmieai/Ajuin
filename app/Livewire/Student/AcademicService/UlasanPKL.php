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

    // Form fields
    public string $judul = '';
    public string $isi = '';
    public int $rating = 0;

    // State
    public bool $showForm = false;
    public bool $showAllUlasan = false;

    // Data
    public ?Submission $submission = null;
    public ?Ulasan $ulasan = null;
    public bool $canReview = false;

    protected function rules(): array
    {
        return [
            'judul'  => 'required|string|min:5|max:100',
            'isi'    => 'required|string|min:20|max:2000',
            'rating' => 'required|integer|min:1|max:5',
        ];
    }

    protected $messages = [
        'judul.required'  => 'Judul ulasan wajib diisi.',
        'judul.min'       => 'Judul minimal 5 karakter.',
        'isi.required'    => 'Isi ulasan wajib diisi.',
        'isi.min'         => 'Ceritakan lebih detail, minimal 20 karakter.',
        'rating.required' => 'Rating wajib dipilih.',
        'rating.min'      => 'Pilih rating minimal 1 bintang.',
    ];

    public function mount(): void
    {
        $studentId = Auth::id();

        $this->submission = Submission::where('user_id', $studentId)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if ($this->submission) {
            $this->canReview = $this->submission->finish_date
                && now()->gte($this->submission->finish_date);

            $this->ulasan = Ulasan::where('submission_id', $this->submission->id)
                ->where('student_id', $studentId)
                ->first();

            if ($this->ulasan) {
                $this->judul  = $this->ulasan->judul;
                $this->isi    = $this->ulasan->isi;
                $this->rating = $this->ulasan->rating;
            }
        }
    }

    public function openForm(): void
    {
        if (! $this->canReview) return;
        $this->showForm = true;
        $this->showAllUlasan = false;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetValidation();

        if ($this->ulasan) {
            $this->judul  = $this->ulasan->judul;
            $this->isi    = $this->ulasan->isi;
            $this->rating = $this->ulasan->rating;
        } else {
            $this->judul  = '';
            $this->isi    = '';
            $this->rating = 0;
        }
    }

    public function toggleAllUlasan(): void
    {
        $this->showAllUlasan = ! $this->showAllUlasan;
        $this->showForm = false;
        $this->resetPage();
    }

    public function save(): void
    {
        if (! $this->canReview) return;

        $this->validate();

        $data = [
            'judul'  => $this->judul,
            'isi'    => $this->isi,
            'rating' => $this->rating,
        ];

        if ($this->ulasan) {
            $this->ulasan->update($data);
            session()->flash('message', 'Ulasan berhasil diperbarui!');
        } else {
            $this->ulasan = Ulasan::create(array_merge($data, [
                'submission_id' => $this->submission->id,
                'student_id'    => Auth::id(),
            ]));
            session()->flash('message', 'Ulasan berhasil dikirim!');
        }

        $this->showForm = false;
    }

    public function render()
    {
        // Preview 2 ulasan terbaru untuk card
        $previewUlasans = Ulasan::with(['student', 'submission'])
            ->latest()
            ->take(1)
            ->get();

        // Semua ulasan untuk modal (dengan pagination)
        $allUlasans = $this->showAllUlasan
            ? Ulasan::with(['student', 'submission'])
                ->latest()
                ->paginate(8, pageName: 'ulasan_page')
            : collect();

        return view('livewire.student.academic-service.ulasan-p-k-l', [
            'previewUlasans' => $previewUlasans,
            'allUlasans'     => $allUlasans,
        ]);
    }
}