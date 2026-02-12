<?php

namespace App\Livewire\Student\Submission;

use App\Models\Submission;
use Illuminate\Container\Attributes\Auth;
use Livewire\Component;

class Create extends Component
{
    public $company_name = "";
    public $company_email = "";
    public $company_phone_number = "";
    public $company_address = "";
    public $start_date = '';
    public $finish_date = '';

    public function create()
    {
    $this->validate([
        'company_name' => 'required',
        'company_email' => 'required|email',
        'company_phone_number' => 'required',
        'company_address' => 'required',
        'start_date' => 'required',
        'finish_date' =>  'required',
    ]);

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




        session()->flash('message', 'Pengajuan menunggu persetujuan guru.');
        $this->reset();
        return redirect('/student');

    }

    public function render()
    {
        return view('livewire.student.submission.create');
    }
}
