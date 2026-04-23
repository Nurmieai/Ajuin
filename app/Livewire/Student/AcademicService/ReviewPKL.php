<?php

namespace App\Livewire\Student\AcademicService;

use App\Models\Review;
use App\Models\Submission;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ReviewPKL extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';
    public string $judul = '';
    public string $isi = '';
    public int $rating = 0;

    public ?Submission $submission = null;
    public ?Review $review = null;
    public bool $canReview = false;
    public ?int $selectedReviewId = null;

    protected function rules(): array
    {
        return [
            'judul'  => 'required|string|min:5|max:100',
            'isi'    => 'required|string|min:20|max:400',
            'rating' => 'required|integer|min:1|max:5',
        ];
    }

    protected $messages = [
        'judul.required'  => 'Judul ulasan wajib diisi.',
        'judul.min'       => 'Judul minimal 5 karakter.',
        'judul.max'       => 'Judul maksimal 100 karakter.',
        'isi.required'    => 'Isi ulasan wajib diisi.',
        'isi.min'         => 'Ceritakan lebih detail, minimal 20 karakter.',
        'isi.max'         => 'Isi ulasan maksimal 400 karakter.',
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

        if (! $this->submission) {
            return;
        }

        $this->canReview = $this->submission->finish_date
            && now()->gte($this->submission->finish_date);

        $this->review = Review::where('submission_id', $this->submission->id)
            ->where('student_id', $studentId)
            ->first();

        if ($this->review) {
            $this->judul  = $this->review->judul;
            $this->isi    = $this->review->isi;
            $this->rating = $this->review->rating;
        }
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openForm(): void
    {
        if (! $this->canReview) return;

        $this->dispatch('open-ulasan-modal');
    }

    public function closeForm(): void
    {
        $this->resetValidation();
        $this->dispatch('close-ulasan-modal');

        if ($this->review) {
            $this->judul  = $this->review->judul;
            $this->isi    = $this->review->isi;
            $this->rating = $this->review->rating;
        } else {
            $this->judul  = '';
            $this->isi    = '';
            $this->rating = 0;
        }
    }

    public function showDetail(int $id): void
    {
        $this->selectedReviewId = $id;
    }

    public function closeDetail(): void
    {
        $this->selectedReviewId = null;
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

        if ($this->review) {
            $this->review->update($data);
            session()->flash('message', 'Ulasan berhasil diperbarui!');
        } else {
            $this->review = Review::create(array_merge($data, [
                'submission_id' => $this->submission->id,
                'student_id'    => Auth::id(),
            ]));
            session()->flash('message', 'Ulasan berhasil dikirim!');
        }

        $this->dispatch('close-ulasan-modal');
    }

    public function render()
    {
        $previewReviews = Review::with(['student', 'submission'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('student', fn($s) => $s->where('username', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('submission', fn($s) => $s->where('company_name', 'like', '%' . $this->search . '%'));
                });
            })
            ->latest()
            ->paginate(4, ['*'], 'page');

        $selectedReview = $this->selectedReviewId
            ? Review::with(['student', 'submission'])->find($this->selectedReviewId)
            : null;

        return view('livewire.student.academic-service.review-pkl', [
            'previewReviews' => $previewReviews,
            'selectedReview' => $selectedReview,
        ]);
    }
}