<?php

namespace App\Livewire\Student\Submission;

use App\Models\Certificates;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class Create extends Component
{
    use WithFileUploads;

    public $company_name = "";
    public $company_email = "";
    public $company_phone_number = "";
    public $company_address = "";
    public $start_date = '';
    public $finish_date = '';

    #[Validate('required', message: 'Sertifikat kunjungan industri wajib diupload')]
    #[Validate('file', message: 'File harus berupa dokumen')]
    #[Validate('mimes:pdf,doc,docx,jpg,png', message: 'Format file harus PDF, DOC, DOCX, JPG, atau PNG')]
    #[Validate('max:2048', message: 'Ukuran file maksimal 2MB')]
    public $industrial_visit;

    #[Validate('required', message: 'Sertifikat uji kompetensi wajib diupload')]
    #[Validate('file', message: 'File harus berupa dokumen')]
    #[Validate('mimes:pdf,doc,docx,jpg,png', message: 'Format file harus PDF, DOC, DOCX, JPG, atau PNG')]
    #[Validate('max:2048', message: 'Ukuran file maksimal 2MB')]
    public $competency_test;

    #[Validate('required', message: 'Kartu SPP wajib diupload')]
    #[Validate('file', message: 'File harus berupa dokumen')]
    #[Validate('mimes:pdf,doc,docx,jpg,png', message: 'Format file harus PDF, DOC, DOCX, JPG, atau PNG')]
    #[Validate('max:2048', message: 'Ukuran file maksimal 2MB')]
    public $spp_card;

    public function mount()
    {
        $HasSubmit = Submission::where('user_id', auth()->id())->where('submission_type', 'mandiri')->where('status', 'submitted')->count();
        $hasApprovedSubmission = Submission::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->exists();

        if ($HasSubmit >= 3) {
            session()->flash('error', 'Anda sudah mencapai batas pengajuan aktif');
            return $this->redirectRoute('student.submission-manage', navigate:true);
        }

        if ($hasApprovedSubmission) {
            session()->flash('error', 'Anda sudah memiliki pengajuan yang diterima');
            return $this->redirectRoute('student.submission-manage', navigate:true);
        }
    }

    /**
     * Reset validation error saat property diupdate
     * Ini memastikan error hilang saat user upload file baru
     */
    public function updated($propertyName)
    {
        // Reset validation untuk property yang diupdate
        $this->resetValidation($propertyName);

        // Validasi real-time untuk file uploads
        if (in_array($propertyName, ['industrial_visit', 'competency_test', 'spp_card'])) {
            $this->validateOnly($propertyName);
        }
    }

    public function create()
    {

        $this->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone_number' => 'required|phone:ID',
            'company_address' => 'required|string',
            'start_date' => 'required|date|before:finish_date',
            'finish_date' => 'required|date|after_or_equal:start_date',
            'industrial_visit' => 'required|max:2048|mimes:pdf,jpeg,jpg,doc,docx,pdf,png',
            'competency_test' => 'required|max:2048|mimes:pdf,jpeg,jpg,doc,docx,pdf,png',
            'spp_card' => 'required',
        ], [
            'company_name.required' => 'Nama perusahaan wajib diisi',
            'company_email.required' => 'Email perusahaan wajib diisi',
            'company_email.email' => 'Format email tidak valid',
            'company_phone_number.required' => 'Nomor telepon wajib diisi',
            'company_phone_number.phone' => 'Masukkan nomor telepon yang valid',
            'company_address.required' => 'Alamat perusahaan wajib diisi',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'start_date.before' => 'Tanggal mulai wajib diisi sebelum tanggal selesai',
            'finish_date.required' => 'Tanggal selesai wajib diisi',
            'finish_date.after_or_equal' => 'Tanggal selesai wajib diisi setelah atau sama dengan tanggal mulai',
            'industrial_visit.mimes' => 'File kunjungan industri harus berformat pdf, doc, docx, jpg, jpeg, atau png',
            'competency_test.mimes' => 'File uji kompetensi harus berformat pdf, doc, docx, jpg, jpeg, atau png',
            'spp_card.mimes' => 'File kartu SPP harus berformat pdf, doc, docx, jpg, jpeg, atau png',
            'industrial_visit.max' => 'Ukuran file maksimal 2MB',
            'competency_test.max' => 'Ukuran file maksimal 2MB',
            'spp_card.max' => 'Ukuran file maksimal 2MB',
            'industrial_visit.required' => 'File Kunjungan Industri Wajib diisi',
            'competency_test.required' => 'File Uji Kompetensi Wajib Diisi',
            'spp_card.required' => 'File Kartu SPP Wajib Diisi',
        ]);
        // Cek lagi sebelum submit
        $HasSubmit = Submission::where('user_id', auth()->id())->where('submission_type', 'mandiri')->where('status', 'submitted')->count();
        $hasApprovedSubmission = Submission::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->exists();

        if ($HasSubmit >= 2) {
            session()->flash('error', 'Anda sudah mencapai batas pengajuan aktif');
            return $this->redirectRoute('student.submission-manage', navigate:true);
        }

        if ($hasApprovedSubmission) {
            session()->flash('error', 'Anda sudah memiliki pengajuan yang diterima');
            return $this->redirectRoute('student.submission-manage', navigate:true);
        }

        // Validasi semua input
        $this->validate();

        try {
            DB::beginTransaction();

            $submission = Submission::create([
                'user_id' => auth()->id(),
                'submission_type' => 'mandiri',
                'company_name' => $this->company_name,
                'company_email' => $this->company_email,
                'company_phone_number' => $this->company_phone_number,
                'company_address' => $this->company_address,
                'start_date' => $this->start_date,
                'finish_date' => $this->finish_date
            ]);

            $certificates = [
                ['file' => $this->industrial_visit, 'type' => 'industrial_visit'],
                ['file' => $this->competency_test, 'type' => 'competency_test'],
                ['file' => $this->spp_card, 'type' => 'spp_card']
            ];

            foreach ($certificates as $certificate) {
                $filePath = $certificate['file']->store(
                    'certificates/submission_' . $submission->id,
                    'public'
                );

                Certificates::create([
                    'submission_id' => $submission->id,
                    'file_path' => $filePath,
                    'type' => $certificate['type']
                ]);
            }

            DB::commit();

            session()->flash('success', 'Pengajuan berhasil dibuat dan menunggu persetujuan guru');

            // Reset semua property
            $this->reset([
                'company_name',
                'company_email',
                'company_phone_number',
                'company_address',
                'start_date',
                'finish_date',
                'industrial_visit',
                'competency_test',
                'spp_card'
            ]);

            // Reset validation errors
            $this->resetValidation();

            $this->redirectRoute('student.submission-create', navigate:true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating submission: ' . $e->getMessage());

            session()->flash('error', 'Terjadi kesalahan, silahkan coba lagi');
            return;
        }
    }

    public function render()
    {
        return view('livewire.student.submission.create');
    }
}
