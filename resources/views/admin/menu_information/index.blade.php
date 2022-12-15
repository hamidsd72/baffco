@component('layouts.admin')
@section('title') {{ $title }} @endsection
@section('body')
{{-- <style>
    h1, h2, h3, h4, h5, h6 {
        font-size: 12px;
    }
</style> --}}
    <div class="condition pull-right w-100 mb-2">
        <div class="title">
            <h5><i class="fa fa-trello text-size-large ml-2"></i>{{ $title }}</h5>
        </div>
        <div class="p-3">
            @if($id??'') 
                <a href="{{ route('admin-menu-information-custom-create',$id) }}" class="btn btn-success mb-2"><i class="fa fa-plus ml-2"></i>افزودن صفحه</a>
            @else 
                <a href="{{ route('admin-menu-information.create') }}" class="btn btn-success mb-2"><i class="fa fa-plus ml-2"></i>افزودن صفحه</a>
            @endif
            <div class="form-group float-left">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if(isset($id)) {{App\Menu::find($id)->name}} @else لیست صفحات @endif
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @foreach($menus as $item)
                            @unless ($item->name=='text')
                                <li class="p-1 px-2"><a style="color: gray" href="{{route('admin-menu-information-filter-by-id',$item->id)}}" title="فیلتر کردن این صفحه">{{$item->name}}</a></li>
                            @endunless
                        @endforeach
                    </div>
                </div>
            </div>
            <table class="table table-bordered pull-right w-100">
                <thead>
                <tr>
                    <th data-hide="phone">صفحه</th>
                    <th data-toggle="true">محتوا</th>
                    {{-- <th data-hide="phone">سکشن</th> --}}
                    <th data-toggle="true">ترتیب نمایش</th>                    
                    <th data-hide="phone">عرض صفحه</th>
                    <th data-toggle="true">وضعیت</th>
                    <th data-hide="phone">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @if($items->count())
                    @foreach($items as $item)
                        <tr> 
                            <td>{{ $item->menu?$item->menu->name:'________' }}</td>
                            <td style="max-width: 260px;max-height: 40px;">{!! $item->text !!}</td>
                            {{-- <td>{{ $item->section_number }}</td> --}}
                            <td>{{ $item->sort }}</td>
                            <td>{{ $item->position }}</td>
                            <td>
                                <span class="{{$item->status=='active'? 'text-danger' : 'text-success'}}">
                                    {{$item->status=='active'? 'عدم نمایش' : 'نمایش'}}
                                    {{-- active and pending status method --}}
                                    <a href="{{route('admin-menu-information.show', $item->id)}}" title="{{$item->status=='active'? ' نمایش' : 'عدم نمایش'}}" style="margin-right: 10px">
                                        @if($item->status=='active') <i class='fa fa-close' style='color: red;'></i> @else <i class='fa fa-check' style='color:darkgreen;'></i> @endif
                                    </a>
                                </span>
                            </td>
                            <td class="table-td-icons" style="display: flex;justify-content: center;align-items: center;min-height: 80px">
                                <a href="{{ route('admin-menu-information.edit', $item->id) }}" title="ویرایش"><i class="far fa-edit"></i></a>
                                {{-- @role('ادمین ارشد') --}}
                                <form action="{{route('admin-menu-information.destroy', $item->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: transparent;border: none;"><i class="fa fa-trash" style="color: red;"></i></button>
                                </form>
                                {{-- @endrole --}}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        {{ $items->appends(request()->query())->links() }}
    </div>
@endsection
@endcomponent

