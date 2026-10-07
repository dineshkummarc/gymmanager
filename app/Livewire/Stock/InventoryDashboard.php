<?php
namespace App\Livewire\Stock;
use Livewire\Component;
use App\Models\{Product, StockMovement};
class InventoryDashboard extends Component {
    public string $name=''; public $price=0; public int $stock=0;
    public function save(){ $this->validate(['name'=>'required']); $p=Product::create(['name'=>$this->name,'price'=>$this->price,'stock'=>$this->stock,'branch_id'=>auth()->user()->branch_id]); StockMovement::create(['product_id'=>$p->id,'branch_id'=>$p->branch_id,'type'=>'in','quantity'=>$this->stock,'reason'=>'Cadastro inicial','user_id'=>auth()->id()]); $this->reset(['name','price','stock']); }
    public function move($id,$type){ $p=Product::findOrFail($id); $p->increment('stock',$type==='in'?1:-1); StockMovement::create(['product_id'=>$id,'type'=>$type,'quantity'=>1,'reason'=>'Ajuste manual','user_id'=>auth()->id()]); }
    public function render(){ return view('livewire.stock.inventory-dashboard',['products'=>Product::orderBy('name')->paginate(12),'low'=>Product::low()->count(),'total'=>(float)Product::selectRaw('sum(price*stock) as t')->value('t')])->layout('layouts.app'); }
}
