<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Student;
class GlobalSearch extends Component {
    public string $q=''; public $open=false;
    public function render(){
        $res = strlen($this->q)>=2 ? [
            'students'=>Student::search($this->q)->limit(5)->get(),
            'leads'=>\App\Models\Lead::where('name','like',"%{$this->q}%")->limit(4)->get(),
            'products'=>\App\Models\Product::where('name','like',"%{$this->q}%")->limit(4)->get(),
        ] : [];
        return view('livewire.global-search',compact('res'));
    }
}
