@component('layouts.admin')
@section('title') {{ $title }} @endsection
@section('body')
    <div class="condition pull-right w-100 mb-2">
        <div class="title">
            <h5><i class="fa fa-trello text-size-large ml-2"></i>{{ $title }}</h5>
        </div>
        <div class="p-3">
            <a href="{{ route('admin-product-create') }}" class="btn btn-success mb-2"><i
                        class="fa fa-plus ml-2"></i>افزودن محصول</a>
            <form action="{{ route('search-product') }}" method="get" enctype="multipart/form-data"
                  class="form-horizontal mt-2 mb-2">
                <div class="row">
                    <div class="col-md-9" style="padding: 0px;display: inline-flex;">

                        <input class="form-control" name="id" value="" placeholder="شماره را وارد کنید">
                        <input class="form-control" name="name" value="" placeholder="نام محصول را وارد کنید">
                        <select name="category_id" id="category_id" class="select form-control">
                            <option value="">دسته بندی را انتخاب کنید</option>
                            @foreach($category as $cat)
                                @php
                                    $parent = App\ProductCategory::find($cat->parent_id);
                                @endphp
                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                            @endforeach
                        </select>
                        {{csrf_field()}}
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">جستجو</button>
                    </div>
                </div>
            </form>
            <table class="table table-data table-togglable table-bordered pull-right w-100">
                <thead>
                <tr>
                    <th data-toggle="true">شماره</th>
                    <th data-toggle="true">نام محصول</th>
                    <th data-hide="phone">دسته بندی</th>
                    <th data-hide="phone">تاریخ ثبت</th>
                    <th data-toggle="true">نمایش/عدم نمایش</th>
                    <th data-toggle="true"> نمایش/عدم نمایش صفحه اصلی</th>
                    {{--                        <th data-hide="phone">مدل ها</th>--}}

                    <th data-hide="phone">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    @php
                        $parent = App\ProductCategory::find($item->category->parent_id);
                    @endphp
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>@if($parent){{$parent->name}}
                            > @endif{{ $item->category? $item->category->name:'ندارد' }}</td>
                        <td>{{ my_jdate($item->created_at, 'Y/m/d') }}</td>


                        <td>
                            @if($item->site=='active') <span class="text-success">نمایش</span> @else <span
                                    class="text-danger">عدم نمایش</span> @endif
                            @if($item->site == 'pending')
                                <a href="{{route('admin-product-active',['site',$item->id])}}" title="نمایش در سایت"
                                   style="margin-right: 10px"><i class="fa fa-check"
                                                                 style="color: darkgreen;"></i></a>
                            @endif
                            @if($item->site == 'active')
                                <a href="{{route('admin-product-active',['site',$item->id])}}"
                                   title="عدم نمایش در سایت" style="margin-right: 10px"><i class="fa fa-close"
                                                                                           style="color: red;"></i></a>
                            @endif
                        </td>
                        <td>
                            @if($item->status_home=='active') <span class="text-success">نمایش</span> @else <span
                                    class="text-danger">عدم نمایش</span> @endif
                            @if($item->status_home == 'pending')
                                <a href="{{route('admin-product-active',['status_home',$item->id])}}"
                                   title="نمایش در صفحه اصلی" style="margin-right: 10px"><i class="fa fa-check"
                                                                                            style="color: darkgreen;"></i></a>
                            @endif
                            @if($item->status_home == 'active')
                                <a href="{{route('admin-product-active',['status_home',$item->id])}}"
                                   title="عدم نمایش در صفحه اصلی" style="margin-right: 10px"><i class="fa fa-close"
                                                                                                style="color: red;"></i></a>
                            @endif
                        </td>
                        {{--                            <td>--}}
                        {{--                                @if(count($item->attributes) > 0)--}}

                        {{--                                    <a href="{{route('admin-model-list' , $item->id)}}">مدل ها</a>--}}

                        {{--                                @else--}}
                        {{--                                    فاقد زیر دسته--}}
                        {{--                                @endif--}}
                        {{--                            </td>--}}
                        
                        <td class="table-td-icons"
                            style="display: flex;justify-content: center;align-items: center;min-height: 80px">
                            <a href="{{ route('admin-product-edit', $item->id) }}" title="ویرایش"><i
                                        class="far fa-edit"></i></a>
                            @role('ادمین ارشد')
                            <a href="{{route('admin-product-destroy',$item->id)}}" title="حذف"><i
                                        class="fa fa-trash" style="color: red;"></i></a>
                            @endrole
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
