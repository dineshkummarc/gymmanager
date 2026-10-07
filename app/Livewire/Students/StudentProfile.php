<?php
namespace App\Livewire\Students;
use Livewire\Component;
use App\Models\Student;
class StudentProfile extends Component {
    public Student $student; public string $tab='overview';
    public function mount(Student $student){ $this->student=$student->load(['branch','memberships.plan','invoices','checkins','workouts','assessments']); }
    public function render(){ return view('livewire.students.student-profile')->layout('layouts.app'); }
}
