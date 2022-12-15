@component('layouts.admin')
@section('title') {{ $title }} @endsection
@section('body')
    <div class="condition pull-right w-100 mb-2">
        <div class="title">
            <h5><i class="fa fa-trello text-size-large ml-2"></i>{{ $title }}</h5>
        </div>
        <div class="p-3">
            <a href="{{ route('admin-menu-create') }}" class="btn btn-success mb-2"><i class="fa fa-plus ml-2"></i>افزودن صفحه</a>
            <table class="table table-bordered pull-right w-100">
                <thead>
                <tr>
                    <th data-toggle="true">عنوان صفحه</th>
                    {{-- <th data-hide="phone">مکان</th>
                    <th data-hide="phone">آدرس url</th> --}}
                    <th data-hide="phone">مرتب سازی</th>
                    <th data-hide="phone">مدیریت محتوا</th>
                    <th data-toggle="true">نمایش/عدم نمایش </th>
                    {{-- <th data-toggle="true">نمایش/عدم نمایش <br> (زیر اسلایدر)</th>
                    <th data-toggle="true">نمایش/عدم نمایش <br> نمایش در هدر</th>
                    <th data-toggle="true">نمایش/عدم نمایش <br> (فوتر)</th> --}}
                    <th data-hide="phone">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->name}}</td>
                        {{-- <td>{{ $item->place=='left'?'چپ(در زبان انگلیسی راست)':'راست(در زبان انگلیسی چپ)' }}</td>
                        @if($item->link!=null && $item->link!='#' && $item->link!='')
                            <td class="d-ltr"><a href="{{$item->link}}" target="_blank">{{substr($item->link,0,20)}}</a></td>
                        @elseif(app()->getLocale()=='fa')
                        <td class="d-ltr"><a href="{{route('user.page.show',$item->slug)}}" target="_blank">{{urldecode(route('user.page.show',$item->slug))}}</a> </td>
                        @else
                        <td class="d-ltr"><a href="{{route('user.page.show',$item->slug_en)}}" target="_blank">{{route('user.page.show',$item->slug_en)}}</a> </td>
                        @endif --}}
                        <td>
                            <form method="post" action="{{route('admin-menu-sort',$item->id)}}">
                                @csrf
                                <input style="width: 100px" class="text-center" name="sort_id" type="number" min="0" value="{{$item->sort_id}}" onchange="return this.form.submit()">
                            </form>
                        </td>

                        <td><a href="{{route('admin-menu-information-filter-by-id',$item->id)}}">محتوا صفحه {{' ('.$item->informations()->count().')'}}</a></td>
                        <td>
                            @if($item->status=='active') <span class="text-success">نمایش</span> @else <span
                                    class="text-danger">عدم نمایش</span> @endif
                            @if($item->status == 'pending')
                                <a href="{{route('admin-menu-active',['status',$item->id])}}" title="نمایش در سایت"
                                   style="margin-right: 10px"><i class="fa fa-check"
                                                                 style="color: darkgreen;"></i></a>
                            @endif
                            @if($item->status == 'active')
                                <a href="{{route('admin-menu-active',['status',$item->id])}}"
                                   title="عدم نمایش در سایت" style="margin-right: 10px"><i class="fa fa-close"
                                                                                           style="color: red;"></i></a>
                            @endif
                        </td>
                        {{-- <td>
                            @if($item->slider_down=='active') <span class="text-success">نمایش</span> @else <span
                                    class="text-danger">عدم نمایش</span> @endif
                            @if($item->slider_down == 'pending')
                                <a href="{{route('admin-menu-active',['slider_down',$item->id])}}" title="نمایش "
                                   style="margin-right: 10px"><i class="fa fa-check"
                                                                 style="color: darkgreen;"></i></a>
                            @endif
                            @if($item->slider_down == 'active')
                                <a href="{{route('admin-menu-active',['slider_down',$item->id])}}"
                                   title="عدم نمایش" style="margin-right: 10px"><i class="fa fa-close"
                                                                                           style="color: red;"></i></a>
                            @endif
                        </td> --}}
                       {{-- <td>
                            <span class="@if($item->menu_type=='menu_0') text-danger @else text-success @endif">
                                @if($item->menu_type=='menu_1') خدمات @endif
                                @if($item->menu_type=='menu_2') درباره حافظ @endif
                                @if($item->menu_type=='menu_3') معاملات آنلاین @endif
                                @if($item->menu_type=='menu_0') عدم نمایش @endif
                            </span>
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal{{$item->id}}" title="ویرایش"><i class="far fa-edit"></i></a> --}}
                           {{-- @if($item->status_menu=='active') <span class="text-success">نمایش</span> @else <span
                                   class="text-danger">عدم نمایش</span> @endif
                           @if($item->status_menu == 'pending')
                               <a href="{{route('admin-menu-active',['status_menu',$item->id])}}" title="نمایش"
                                  style="margin-right: 10px"><i class="fa fa-check"
                                                                style="color: darkgreen;"></i></a>
                           @endif
                           @if($item->status_menu == 'active')
                               <a href="{{route('admin-menu-active',['status_menu',$item->id])}}"
                                  title="عدم نمایش" style="margin-right: 10px"><i class="fa fa-close"
                                                                                          style="color: red;"></i></a>
                           @endif --}}
                       {{-- </td> --}}
                        {{-- <td>
                            @if($item->status_footer=='active') <span class="text-success">نمایش</span> @else <span
                                    class="text-danger">عدم نمایش</span> @endif
                            @if($item->status_footer == 'pending')
                                <a href="{{route('admin-menu-active',['status_footer',$item->id,0])}}" title="نمایش "
                                   style="margin-right: 10px"><i class="fa fa-check"
                                                                 style="color: darkgreen;"></i></a>
                            @endif
                            @if($item->status_footer == 'active')
                                <a href="{{route('admin-menu-active',['status_footer',$item->id,0])}}"
                                   title="عدم نمایش" style="margin-right: 10px"><i class="fa fa-close"
                                                                                           style="color: red;"></i></a>
                            @endif
                        </td> --}}
                        <td class="table-td-icons"
                            style="display: flex;justify-content: center;align-items: center;min-height: 80px">
                            <a href="{{ route('admin-menu-edit', $item->id) }}" title="ویرایش"><i
                                        class="far fa-edit"></i></a>
                            {{-- @role('ادمین ارشد') --}}
                            <a href="{{route('admin-menu-destroy',$item->id)}}" title="حذف"><i class="fa fa-trash" style="color: red;"></i></a>
                            {{-- @endrole --}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $items->appends(request()->query())->links() }}
    </div>

    @foreach($items as $item)
        <div class="modal" id="myModal{{$item->id}}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{route('admin-menu-active-edit')}}" method="get">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">محل قرارگیری {{$item->name}} در هدر</h4>
                            <button type="button" class="close" data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="{{$item->id}}">
                            <select class="form-control" id="val" name="val">
                                <option value="menu_1" @if($item->menu_type=='menu_1') selected @endif>خدمات</option>
                                <option value="menu_2" @if($item->menu_type=='menu_2') selected @endif>درباره حافظ</option>
                                <option value="menu_3" @if($item->menu_type=='menu_3') selected @endif>معاملات آنلاین</option>
                                <option value="menu_0" @if($item->menu_type=='menu_0') selected @endif>عدم نمایش</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">ویرایش</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection
@push('scripts')
    <script>
        $(".modal_btn").click(function () {
            var id = $(this).attr("id");
            $(".id").val(id);
        })
    </script>
@endpush
@endcomponent
