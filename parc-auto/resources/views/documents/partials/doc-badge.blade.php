@php
    $doc = $vehicle->documents->where('type', $type)->first();
@endphp

<div class="flex items-center justify-between text-xs p-1.5 rounded-lg border {{ $doc ? 'bg-white border-gray-100' : 'bg-gray-50/50 border-dashed border-gray-200' }}">
    <span class="font-medium text-gray-500">{{ $label }} :</span>
    
    @if($doc)
        <div class="text-right">
            <span class="font-mono font-bold text-gray-800 text-[11px] block">{{ $doc->document_number }}</span>
            <span class="text-[10px] text-gray-400 block">Délivré le : {{ $doc->issued_at->format('d/m/Y') }}</span>
            @if($doc->expires_at)
                @if($doc->expires_at->isPast())
                    <span class="text-[9px] font-black uppercase text-red-600 bg-red-50 px-1 rounded">⚠️ Périmé</span>
                @else
                    <span class="text-[9px] text-emerald-600 font-medium block">Exp : {{ $doc->expires_at->format('d/m/Y') }}</span>
                @endif
            @endif
        </div>
    @else
        <span class="text-[10px] text-gray-400 italic">Non renseigné</span>
    @endif
</div>