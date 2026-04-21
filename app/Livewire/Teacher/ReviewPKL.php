<?php

namespace App\Livewire\Teacher;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewPKL extends Component
{
    use WithPagination;

    public string $search     = '';
    public ?int $filterRating = null;
    public ?Review $selectedReview = null;

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
    }

    public function closeDetail(): void
    {
        $this->selectedReview = null;
    }

    public function resetFilter(): void
    {
        $this->search       = '';
        $this->filterRating = null;
        $this->resetPage();
    }

    public function paginationView(): string
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        $query = Review::with(['student', 'submission'])
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->whereHas('student', fn($s) => $s->where('username', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('submission', fn($s) => $s->where('company_name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->filterRating, fn($q) => $q->where('rating', $this->filterRating))
            ->latest();

        $reviews = $query->paginate(10);

        // Deteksi apakah search cocok ke nama perusahaan (bukan siswa)
        // Ambil company_name unik dari hasil search saat ini
        $foundCompanyNames = $reviews->pluck('submission.company_name')
            ->filter()
            ->unique()
            ->values();

        // Card rating perusahaan hanya muncul jika:
        // - Ada keyword search
        // - Semua hasil mengarah ke 1 perusahaan yang sama (search spesifik perusahaan)
        $companyRatingCard = null;
        if ($this->search && $foundCompanyNames->count() === 1) {
            $companyName = $foundCompanyNames->first();
            // Hitung rata-rata dari SEMUA review di perusahaan itu (tidak hanya halaman ini)
            $avg = Review::whereHas('submission', fn($q) => $q->where('company_name', $companyName))
                ->avg('rating');
            $count = Review::whereHas('submission', fn($q) => $q->where('company_name', $companyName))
                ->count();
            $companyRatingCard = [
                'name'  => $companyName,
                'avg'   => round($avg, 1),
                'count' => $count,
            ];
        }

        return view('livewire.teacher.review-p-k-l', [
            'reviews'           => $reviews,
            'totalCount'        => Review::count(),
            'companyRatingCard' => $companyRatingCard,
        ]);
    }
}