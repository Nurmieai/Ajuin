<?php

namespace App\Livewire\Student\AcademicService\Submission;

use App\Models\Submission;
use App\Models\Certificates;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{

    use WithFileUploads;

    public Submission $submission;

    public $company_name;
    public $company_email;
    public $company_phone_number;
    public $company_address;
    public $start_date;
    public $finish_date;

    public $industrial_visit;
    public $competency_test;
    public $spp_card;

    public $existing_industrial_visit;
    public $existing_competency_test;
    public $existing_spp_card;

    public $filesTodelete = [];
    
    public function mount($id)
    {
        $this->submission = Submission::with('certificates')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if (!$this->submission->canBeEdited()) {
            session()->flash('error', 'Pengajuan tidak dapat diubah');
            return redirect()->route('student.submission-manage');
        }

        $this->company_name = $this->submission->company_name;
        $this->company_email = $this->submission->company_email;
        $this->company_phone_number = $this->submission->company_phone_number;
        $this->company_address = $this->submission->company_address;
        $this->start_date = $this->submission->start_date->format('Y-m-d');
        $this->finish_date = $this->submission->finish_date->format('Y-m-d');

        $this->existing_industrial_visit = $this->submission->getCertificateByType('industrial_visit');
        $this->existing_competency_test = $this->submission->getCertificateByType('competency_test');
        $this->existing_spp_card = $this->submission->getCertificateByType('spp_card');
    }

    private function fileRule($existing){
        return ($existing ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048';
    }

    public function update()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone_number' => 'required|string|max:20',
            'company_address' => 'required|string',
            'start_date' => 'required|date',
            'finish_date' => 'required|date|after_or_equal:start_date',
            'industrial_visit' => $this->fileRule($this->existing_industrial_visit),
            'competency_test' => $this->fileRule($this->existing_competency_test),
            'spp_card' => $this->fileRule($this->existing_spp_card),
        ], [
            'company_name.required' => 'Nama perusahaan wajib diisi',
            'company_email.required' => 'Email perusahaan wajib diisi',
            'company_email.email' => 'Format email tidak valid',
            'company_phone_number.required' => 'Nomor telepon wajib diisi',
            'company_address.required' => 'Alamat perusahaan wajib diisi',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'finish_date.required' => 'Tanggal selesai wajib diisi',
            'finish_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
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

        try {
            DB::beginTransaction();

            $this->submission->update([
                'company_name' => $this->company_name,
                'company_email' => $this->company_email,
                'company_phone_number' => $this->company_phone_number,
                'company_address' => $this->company_address,
                'start_date' => $this->start_date,
                'finish_date' => $this->finish_date,
                'status' => 'submitted',
            ]);

            $this->updateCertificate('industrial_visit', $this->industrial_visit, $this->existing_industrial_visit);
            $this->updateCertificate('competency_test', $this->competency_test, $this->existing_competency_test);
            $this->updateCertificate('spp_card', $this->spp_card, $this->existing_spp_card);

            foreach ($this->filesTodelete as $type) {

                $certificates = $this->submission->getCertificateByType($type);

                if ($certificates) {
                    if (Storage::disk('public')->exists($certificates->file_path)) {
                        Storage::disk('public')->exists($certificates->file_path);
                    }
                }

                $certificates->delete();
            }

            DB::commit();

            session()->flash('success', 'Pengajuan berhasil diperbarui');
            return redirect()->route('student.submission-manage');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function updateCertificate($type, $newFile, $existingCertificate)
    {
        if ($newFile) {
            if ($existingCertificate && Storage::disk('public')->exists($existingCertificate->file_path)) {
                Storage::disk('public')->delete($existingCertificate->file_path);
            }

            $filePath = $newFile->store(
                'certificates/submission_' . $this->submission->id,
                'public'
            );

            if ($existingCertificate) {
                $existingCertificate->update(['file_path' => $filePath]);
            } else {
                Certificates::create([
                    'submission_id' => $this->submission->id,
                    'file_path' => $filePath,
                    'type' => $type
                ]);
            }
        }
    }

    public function removeFile($type)
    {
            if (!in_array($type, $this->filesTodelete)) {
                $this->filesTodelete[] = $type;
            }

            $this->{"existing_$type"} = null;

            session()->flash('success', 'File berhasil dihapus');

    }

    public function cancel()
    {
        return redirect()->route('student.submission-manage');
    }

    public function render()
    {
        return view('livewire.student.academic-service.submission.update');
    }
}
