<?php

namespace App\Livewire\Teacher;

use App\Models\Ulasan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UlasanPKL extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortRating = '';
    public ?int $filterRating = null;

    // Tambahkan properti ini


    public bool $showAllUlasan = false; // State untuk modal "Lihat Semua"

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Fungsi Toggle Modal Ulasan
    public function toggleAllUlasan()
    {
        $this->showAllUlasan = !$this->showAllUlasan;
        if ($this->showAllUlasan) {
            $this->dispatch('open-all-ulasan-modal');
        } else {
            $this->dispatch('close-all-ulasan-modal');
        }
    }





    public function resetFilter(): void
    {
        $this->sortRating = '';
        $this->filterRating = null;
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        $query = Ulasan::with(['student.major', 'submission'])
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('isi', 'like', '%' . $this->search . '%')
                        ->orWhereHas('student', fn($s) => $s->where('fullname', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('submission', fn($s) => $s->where('company_name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->filterRating, fn($q) => $q->where('rating', $this->filterRating))
            ->when($this->sortRating, fn($q) => $q->orderBy('rating', $this->sortRating))
            ->latest();

        return view('livewire.teacher.ulasan-p-k-l', [
            'ulasans'    => $query->paginate(10),
            'totalCount' => Ulasan::count(),
            'avgRating'  => round(Ulasan::avg('rating'), 1),
            // Untuk preview di sidebar (opsional jika dibutuhkan di view)
            'previewUlasans' => Ulasan::with(['student', 'submission'])->latest()->take(2)->get(),
        ]);
    }
}
