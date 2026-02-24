<?php

namespace App\Livewire\Student\Submission;

use App\Models\Certificates;
use App\Models\Submission;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads; 
    
    public $company_name = "";
    public $company_email = "";
    public $company_phone_number = "";
    public $company_address = "";
    public $start_date = '';
    public $finish_date = '';
    public $industrial_visit;   
    public $competency_test;
    public $spp_card;

    public function mount()
    {
        $hasApprovedSubmission = Submission::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->exists();

        if ($hasApprovedSubmission) {
            session()->flash('error', 'Anda sudah memiliki pengajuan yang diterima');
            return redirect()->route('student.submission-manage');
        }
    }

    public function create()
    {
    $hasApprovedSubmission = Submission::where('user_id', auth()->id())
        ->where('status', 'approved')
        ->exists();

    if ($hasApprovedSubmission) {
        session()->flash('error', 'Anda sudah memiliki pengajuan yang diterima');
        return redirect()->route('student.submissions.manage');
    }
        
    $this->validate([
        'company_name' => 'required|max:40',
        'company_email' => 'required|email|max:30',
        'company_phone_number' => 'required|max:14',
        'company_address' => 'required|max:300',
        'start_date' => 'required|date|before:finish_date',
        'finish_date' =>  'required|date|after:start_date',
        'industrial_visit' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'competency_test' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'spp_card' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
    ]);

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
        [
            'file' => $this->industrial_visit,
            'type' => 'industrial_visit'
        ],
        [
            'file' => $this->competency_test,
            'type' => 'competency_test'
        ],
        [
            'file' => $this->spp_card,
            'type' => 'spp_card'
        ]
    ];  

    foreach ($certificates as $certificate)
        {
            $filePath = $certificate['file']->store(
                'certificates/submission_' . $submission->id,
                'public'
            );

            Certificates::create([
                'submission_id' => $submission->id,
                'file_path' => $filePath,
                'type' => $certificate['type']
            ]);
        };

        DB::commit();

        session()->flash('message', 'pengajuan menunggu persetujuan guru');
        $this->reset();

        return redirect()->route('student.submission-create');


    } catch (\Exception $e) {
        DB::rollBack();

        Log::error($e);
        session()->flash('error', 'Terjadi Kesalahan, silahkan coba lagi');
        return;
    }
    }

    public function render()
    {
        return view('livewire.student.submission.create');
    }
}
