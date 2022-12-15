@extends('layouts.user')
@section('body')
    <div class="container my-4 my-lg-5 ">

        <div class="row text-center">
            @if ($page->contents()->count())
                @foreach ($page->contents() as $item)
                    <div class="col-lg-{{$item->position}}">
                        {!! $item->text !!}
                        @if ($item->picture())
                            <img src="{{$item->picture()->pic?url($item->picture()->pic):''}}" alt="{{$page->name}}" width="100%">
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

    </div>
@endsection

