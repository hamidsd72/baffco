@component('layouts.admin')
@section('title') {{ $title }} @endsection
@section('body')
    <div class="condition pull-right w-100 mb-2">
        <div class="title">
            <h5><i class="fa fa-trello text-size-large ml-2"></i>{{ $title }}</h5>
        </div>
        <form action="{{ route('admin-product-category-update', $item->id) }}" method="POST"
              enctype="multipart/form-data" class="form-horizontal">
            <fieldset>
                <div class="form-group{{ $errors->has('sort_id') ? ' has-error' : '' }}">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sort_id" class="form-label">ترتیب دسته بندی :</label>
                            <input type="number" class="form-control" id="sort_id" name="sort_id" min="1"
                                   value="{{ $item->sort_id }}" required/>
                            @if ($errors->has('sort_id'))
                                <span class="help-block"><strong>{{ $errors->first('sort_id') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('sort_menu') ? ' has-error' : '' }}">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sort_menu" class="form-label">ترتیب در منو :</label>
                            <input type="number" class="form-control" id="sort_menu" name="sort_menu" min="1"
                                   value="{{ $item->sort_menu }}" />
                            @if ($errors->has('sort_menu'))
                                <span class="help-block"><strong>{{ $errors->first('sort_menu') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="parent_id" class="form-label">دسته ی مادر :</label>
                            <select name="parent_id" class="form-control">
                                <option value="0">سر دسته اصلی</option>
                                @foreach($items as $val)
                                    <option value="{{$val->id}}" @if($item->parent_id ==$val->id) selected @endif>{{$val->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('parent_id'))
                                <span class="help-block"><strong>{{ $errors->first('parent_id') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                    <div class="col-md-12">
                        <label for="photo" class="form-label">تصویر(در صورت انتخاب تصویر جدید جایگزین می شود) :</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                               value="{{ old('photo') }}"/>
                        @if ($errors->has('photo'))
                            <span class="help-block"><strong>{{ $errors->first('photo') }}</strong></span>
                        @endif
                    </div>
                    @if($item->photo)
                        <div class="col-sm-2 mt-3">
                            <img src="{{url($item->photo->path)}}" alt="">
                        </div>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
                    <div class="col-md-12">
                        <label for="photo" class="form-label">آیکون (png) :</label>
                        <input type="file" class="form-control" id="icon" name="icon" accept="image/png"
                               value="{{ old('icon') }}"/>
                        @if ($errors->has('icon'))
                            <span class="help-block"><strong>{{ $errors->first('icon') }}</strong></span>
                        @endif
                    </div>
                    @if($item->icon)
                        <div class="col-sm-2 mt-3">
                            <img src="{{url($item->icon)}}" style="background: rgba(0,0,0,0.1)" alt="">
                        </div>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('icon_hover') ? ' has-error' : '' }}">
                    <div class="col-md-6">
                        <label for="photo" class="form-label">آیکون_هاور (png) :</label>
                        <input type="file" class="form-control" id="icon_hover" name="icon_hover" accept="image/png"
                               value="{{ old('icon_hover') }}"/>
                        @if ($errors->has('icon_hover'))
                            <span class="help-block"><strong>{{ $errors->first('icon_hover') }}</strong></span>
                        @endif
                    </div>
                    @if($item->icon_hover)
                        <div class="col-sm-2 mt-3">
                            <img src="{{url($item->icon_hover)}}" style="background: rgba(0,0,0,0.1)" alt="">
                        </div>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('short_text') ? ' has-error' : '' }}">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="text" class="form-label">توضیحات کوتاه فارسی : </label>
                            <textarea class="textarea ckeditor form-control" id="text" name="text" cols="5"
                                      rows="10">{{$item->text}}</textarea>
                            @if ($errors->has('short_text'))
                                <span class="help-block"><strong>{{ $errors->first('text') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 tab_box my-3 py-3 px-0">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="fa-tab" data-toggle="tab" href="#fa" role="tab"
                               aria-controls="farsi" aria-selected="true"> فارسی</a>
                        </li>

                    </ul>
                    <div class="tab-content py-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="fa" role="tabpanel" aria-labelledby="farsi-tab">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">نام دسته بندی :</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{ $item->name }}"
                                               required/>
                                        <label for="name" class="form-label">* slug :</label>
                                        <input class="form-control" id="slug" name="slug"
                                               value="{{ $item->slug }}"
                                               required/>
                                        @if ($errors->has('name'))
                                            <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                                        @endif
                                        <label for="short_text" class="form-label">* توضیحات کوتاه (صفحه اصلی) :</label>
                                        <input class="form-control" id="short_text" name="short_text" value="{{ $item->short_text }}" required/>
                                    </div>
                                </div>
                            </div>

                          {{--  <div class="form-group{{ $errors->has('keyword') ? ' has-error' : '' }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">کلمات کلیدی :</label>
                                        <input type="text" class="form-control" id="keyword" name="keyword"
                                               value="{{ $item->keyword }}"
                                               placeholder="با کاما '،' کلمات را ازهم جدا کنید"/>
                                        @if ($errors->has('keyword'))
                                            <span class="help-block"><strong>{{ $errors->first('keyword') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('dis') ? ' has-error' : '' }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">توضیحات سئو :</label>
                                        <input type="text" class="form-control" id="dis" name="dis"
                                               value="{{ $item->dis }}"/>
                                        @if ($errors->has('dis'))
                                            <span class="help-block"><strong>{{ $errors->first('dis') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>--}}
                        </div>
                        <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="english-tab">


                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-success pull-left mr-2"><i
                                        class="fa fa-check ml-2"></i>ثبت شود
                            </button>
                            <button type="reset" class="btn btn-default pull-left"><i class="fa fa-refresh ml-2"></i>بازنشانی
                            </button>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
@endsection
@push('scripts')
    {{-- <script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>--}}
    <script src="{{asset('editor/laravel-ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('editor/laravel-ckeditor/adapters/jquery.js')}}"></script>

    <script type="text/javascript">
        $(".textarea").ckeditor({
            filebrowserImageBrowseUrl: "{{ url('filemanager?type=Images') }}",
            filebrowserImageUploadUrl: "{{ url('filemanager/upload?type=Images&_token=') }}",
            filebrowserBrowseUrl: "{{ url('filemanager?type=Files') }}",
            filebrowserUploadUrl: "{{ url('filemanager/upload?type=Files&_token=') }}",
            language: 'fa'
        });
    </script>


@endpush
@endcomponent
