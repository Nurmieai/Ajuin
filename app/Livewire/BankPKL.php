<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Submission;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class BankPKL extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $startDate = '';

    #[Url(history: true)]
    public $endDate = '';

    #[Url(history: true)]
    public $status = '';

    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        $user = auth()->user();

        $submissions = Submission::with('user')
            ->when($this->status !== '', function ($query) {
                $statusMap = [
                    'menunggu'  => 'pending',
                    'disetujui' => 'approved',
                    'ditolak'   => 'rejected',
                ];

                $inputStatus = strtolower(trim($this->status));
                $dbStatus = array_key_exists($inputStatus, $statusMap)
                    ? $statusMap[$inputStatus]
                    : $this->status;

                return $query->where('status', $dbStatus);
            }, function ($query) {
                return $query->where('status', 'approved');
            })
            ->when($this->startDate !== '', function ($query) {
                return $query->whereDate('start_date', '>=', $this->startDate);
            })
            ->when($this->endDate !== '', function ($query) {
                return $query->whereDate('finish_date', '<=', $this->endDate);
            })
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                        ->orWhere('start_date', 'like', '%' . $this->search . '%')
                        ->orWhere('finish_date', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($subQuery) {
                            $subQuery->where('fullname', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.bank-p-k-l', ['submissions' => $submissions]);
    }
}