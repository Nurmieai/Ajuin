<?php

namespace App\Livewire\Teacher;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewPKL extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortRating = '';
    public ?int $filterRating = null;
    public ?Review $selectedReview = null;

    public function updatedSearch(): void
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
        $this->sortRating   = '';
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
                    $q2->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('isi', 'like', '%' . $this->search . '%')
                        ->orWhereHas('student', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('submission', fn($s) => $s->where('company_name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->filterRating, fn($q) => $q->where('rating', $this->filterRating))
            ->when($this->sortRating, fn($q) => $q->orderBy('rating', $this->sortRating))
            ->latest();

        return view('livewire.teacher.review-p-k-l', [
            'reviews'    => $query->paginate(10),
            'totalCount' => Review::count(),
            'avgRating'  => round(Review::avg('rating'), 1),
        ]);
    }
}