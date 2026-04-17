<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Submission;
use Livewire\WithPagination;
use Livewire\Attributes\Url; // Tambahkan ini agar search tersimpan di URL

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
            // Filter berdasarkan Status (Dukungan Bahasa Indonesia)
            ->when($this->status !== '', function ($query) {
                // Mapping kata kunci bahasa Indonesia ke value bahasa Inggris di database
                $statusMap = [
                    'menunggu'  => 'pending',
                    'disetujui' => 'approved',
                    'ditolak'   => 'rejected',
                ];

                $inputStatus = strtolower(trim($this->status));
                $dbStatus = array_key_exists($inputStatus, $statusMap) ? $statusMap[$inputStatus] : $this->status;

                return $query->where('status', $dbStatus);
            }, function ($query) {
                // Default jika tidak ada pencarian status
                return $query->where('status', 'approved');
            })
            // Filter berdasarkan Role
            ->when($user->hasRole('student'), function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            // Filter berdasarkan Tanggal Mulai
            ->when($this->startDate !== '', function ($query) {
                return $query->whereDate('start_date', '>=', $this->startDate);
            })
            // Filter berdasarkan Tanggal Selesai
            ->when($this->endDate !== '', function ($query) {
                return $query->whereDate('end_date', '<=', $this->endDate);
            })
            // Filter berdasarkan Search (Teks Umum)
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                        ->orWhere('start_date', 'like', '%' . $this->search . '%') // Tambahan pencarian di tanggal mulai
                        ->orWhere('end_date', 'like', '%' . $this->search . '%')   // Tambahan pencarian di tanggal selesai
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
