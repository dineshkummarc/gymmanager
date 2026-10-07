<?php
namespace App\Livewire\Students;
use Livewire\Component; use Livewire\WithPagination;
use App\Models\Student;
class StudentTable extends Component {
    use WithPagination;
    public string $search=''; public string $status=''; public $branch='';
    public function updatingSearch(){ $this->resetPage(); }
    public function delete($id){ $this->authorize('delete', Student::findOrFail($id)); Student::findOrFail($id)->delete(); session()->flash('ok','Aluno excluído.'); }
    public function render() {
        $q = Student::with(['branch','activeMembership.plan'])->orderBy('name');
        if($this->search) $q->search($this->search);
        if($this->status) $q->where('status',$this->status);
        if($this->branch) $q->where('branch_id',$this->branch);
        return view('livewire.students.student-table',['students'=>$q->paginate(12),'branches'=>\App\Models\Branch::orderBy('name')->get()])->layout('layouts.app');
    }
}
