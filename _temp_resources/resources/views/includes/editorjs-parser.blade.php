@php
    $data = json_decode($content ?? '');
    $isEditorJs = $data && isset($data->blocks) && is_array($data->blocks);
@endphp

@if($isEditorJs)
    <div class="editorjs-content animate-fade-in">
        @foreach($data->blocks as $block)
            @switch($block->type)
                @case('header')
                    <h{{ $block->data->level ?? 2 }} class="font-bold my-4 {{ ($block->data->level ?? 2) == 2 ? 'text-2xl' : 'text-xl' }}">
                        {!! $block->data->text !!}
                    </h{{ $block->data->level ?? 2 }}>
                    @break

                @case('paragraph')
                    <p class="mb-4 leading-relaxed text-gray-700">{!! $block->data->text !!}</p>
                    @break

                @case('list')
                    @php $tag = ($block->data->style ?? 'unordered') === 'ordered' ? 'ol' : 'ul'; @endphp
                    <{{ $tag }} class="mb-4 pl-5 {{ $tag === 'ol' ? 'list-decimal' : 'list-disc' }}">
                        @foreach($block->data->items as $item)
                            <li class="mb-1">{!! $item !!}</li>
                        @endforeach
                    </{{ $tag }}>
                    @break

                @case('image')
                     <figure class="my-6">
                        <img src="{{ $block->data->file->url }}" alt="{{ $block->data->caption ?? '' }}" class="img-fluid rounded-lg shadow-md mx-auto" style="max-height: 500px;">
                        @if(!empty($block->data->caption))
                            <figcaption class="text-center text-sm text-gray-500 mt-2">{!! $block->data->caption !!}</figcaption>
                        @endif
                     </figure>
                    @break

                @case('table')
                    @if(!empty($block->data->content))
                        <div class="overflow-x-auto my-4">
                            <table class="table table-bordered w-full border-collapse">
                                <tbody>
                                    @foreach($block->data->content as $row)
                                        <tr>
                                            @foreach($row as $cell)
                                                <td class="border p-2">{!! $cell !!}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @break

                @default
                    <!-- Fallback for unknown blocks -->
            @endswitch
        @endforeach
    </div>
@else
    <!-- Legacy HTML Content -->
    <div class="legacy-content">
        {!! $content !!}
    </div>
@endif
