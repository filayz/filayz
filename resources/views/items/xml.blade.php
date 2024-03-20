@php /** @var \App\Models\Item $item */ @endphp
<type name="{{ $item->name }}">
    @if($item->nominal)<nominal>{{ $item->nominal }}</nominal>
    @endif
    @if($item->lifetime)<lifetime>{{ $item->lifetime }}</lifetime>
    @endif
    @if($item->restock)<restock>{{ $item->restock }}</restock>
    @endif
    @if($item->min)<min>{{ $item->min }}</min>
    @endif
    @if($item->quantmin)<quantmin>{{ $item->quantmin }}</quantmin>
    @endif
    @if($item->quantmax)<quantmax>{{ $item->quantmax }}</quantmax>
    @endif
    @if($item->cost)<cost>{{ $item->cost }}</cost>
    @endif
    <flags count_in_cargo="0" count_in_hoarder="0" count_in_map="1" count_in_player="0" crafted="0" deloot="0"/>
    @foreach($item->categories as $category)
    <category name="{{ $category->name }}"/>

    @endforeach
    @foreach($item->tags as $tag)
    <tag name="{{ $tag->name }}"/>

    @endforeach
    @foreach($item->areas as $area)
    <usage name="{{ $area->name }}"/>
        
    @endforeach
</type>
