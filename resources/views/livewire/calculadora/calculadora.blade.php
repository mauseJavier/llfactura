<div>
    
    <div  style="text-align:center">
        {{--
        <input class="hidden" wire:keydown.escape="clear">
        <input class="hidden" wire:keydown.shift.1="negate">
        <input class="hidden" wire:keydown.shift.5="percent">
        <input class="hidden" wire:keydown.add="add">
        <input class="hidden" wire:keydown.subtract="subtract">
        <input class="hidden" wire:keydown.multiply="multiply">
        <input class="hidden" wire:keydown.divide="divide">
        <input class="hidden" wire:keydown.decimal="decimal">
        <input class="hidden" wire:keydown.0="number(0)">
        <input class="hidden" wire:keydown.1="number(1)">
        <input class="hidden" wire:keydown.2="number(2)">
        <input class="hidden" wire:keydown.3="number(3)">
        <input class="hidden" wire:keydown.4="number(4)">
        <input class="hidden" wire:keydown.5="number(5)">
        <input class="hidden" wire:keydown.6="number(6)">
        <input class="hidden" wire:keydown.7="number(7)">
        <input class="hidden" wire:keydown.8="number(8)">
        <input class="hidden" wire:keydown.9="number(9)">
        <input class="hidden" wire:keydown.enter="equal">
        --}}
    

    
        <table>
            <tr>      
                <h1>{{ $display }}</h1>
            </tr>
        
            <tr>        
                <button style="width: 20%; margin-inline: 5px" wire:click="clear" title="Clear">{{ $this->clearText }}</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="negate" title="Negate">&plusmn;</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="percent" title="Percent">&percnt;</button>
                <button style="width: 20%; margin-inline: 5px" {{ $selectedOperator == '/' ? 'border-2' : 'border border-b-0' }}" wire:click="divide" title="Divide">&divide;</button>
            </tr>
        
            <tr>        
                <button style="width: 20%; margin-inline: 5px" wire:click="number(7)">7</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="number(8)">8</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="number(9)">9</button>
                <button style="width: 20%; margin-inline: 5px" {{ $selectedOperator == '*' ? 'border-2' : 'border border-b-0' }}" wire:click="multiply" title="Multiply">&times;</button>
            </tr>
        
            <tr>        
                <button style="width: 20%; margin-inline: 5px" wire:click="number(4)">4</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="number(5)">5</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="number(6)">6</button>
                <button style="width: 20%; margin-inline: 5px" {{ $selectedOperator == '-' ? 'border-2' : 'border border-b-0' }}" wire:click="subtract" title="Subtract">&minus;</button>
            </tr>
        
            <tr>        
                <button style="width: 20%; margin-inline: 5px" wire:click="number(1)">1</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="number(2)">2</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="number(3)">3</button>
                <button style="width: 20%; margin-inline: 5px" {{ $selectedOperator == '+' ? 'border-2' : 'border border-b-0' }}" wire:click="add" title="Add">&plus;</button>
            </tr>

            <tr>
                <button style="width: 20%; margin-inline: 5px" wire:click="number(0)">0</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="decimal">&middot;</button>
                <button style="width: 20%; margin-inline: 5px" wire:click="equal" title="Equal">&equals;</button>
            </tr>
        </table>
    
        <div cosa="sm:block md:flex mt-16 border border-gray-400 bg-gray-100">
            <div cosa="md:w-1/4 bg-gray-100 h-18 text-left sm:text-2xl md:text-3xl font-bold px-4">Stack</div>
            <div cosa="md:w-3/4 bg-gray-100 h-18 sm:text-left md:text-right sm:text-2xl md:text-3xl px-4">{{ $stack }}</div>
        </div>
    </div>

</div>
