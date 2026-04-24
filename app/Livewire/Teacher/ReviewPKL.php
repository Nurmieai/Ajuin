<?php

namespace App\Livewire\Teacher;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewPKL extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterRating = null;
    public ?Review $selectedReview = null;
    public $companyAverageRating = 0; // Tambahan untuk menyimpan rata-rata rating modal

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterRating(): void
    {
        $this->resetPage();
    }

    public function showDetail(int $id): void
    {
        $this->selectedReview = Review::with(['student', 'submission'])->find($id);

        // Menghitung rata-rata rating perusahaan berdasarkan company_name untuk modal
        if ($this->selectedReview && $this->selectedReview->submission && $this->selectedReview->submission->company_name) {
            $companyName = $this->selectedReview->submission->company_name;
            $this->companyAverageRating = Review::whereHas('submission', function ($q) use ($companyName) {
                $q->where('company_name', $companyName);
            })->avg('rating') ?? 0;
        } else {
            $this->companyAverageRating = 0;
        }

        // Memicu event browser untuk ditangkap oleh @open-detail-modal.window di Alpine
        $this->dispatch('open-detail-modal');
    }

    public function closeDetail(): void
    {
        $this->selectedReview = null;
        $this->companyAverageRating = 0; // Reset nilai

        // Memicu event browser untuk menutup native dialog
        $this->dispatch('close-detail-modal');
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->filterRating = null;
        $this->resetPage();
    }

    public function paginationView(): string
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        $reviews = Review::with(['student', 'submission'])
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->whereHas('student', fn($s) => $s->where('username', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('submission', fn($s) => $s->where('company_name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->filterRating, fn($q) => $q->where('rating', $this->filterRating))
            ->latest()
            ->paginate(10);

        $companyRatingCard = $this->resolveCompanyRatingCard($reviews);

        return view('livewire.teacher.review-p-k-l', [
            'reviews'           => $reviews,
            'totalCount'        => Review::count(),
            'companyRatingCard' => $companyRatingCard,
        ]);
    }

    private function resolveCompanyRatingCard($reviews): ?array
    {
        if (! $this->search) {
            return null;
        }

        $uniqueCompanies = $reviews->pluck('submission.company_name')
            ->filter()
            ->unique()
            ->values();

        if ($uniqueCompanies->count() !== 1) {
            return null;
        }

        $companyName = $uniqueCompanies->first();

        $companyReviews = Review::whereHas('submission', fn($q) => $q->where('company_name', $companyName));

        return [
            'name'  => $companyName,
            'avg'   => round($companyReviews->avg('rating'), 1),
            'count' => $companyReviews->count(),
        ];
    }
}
