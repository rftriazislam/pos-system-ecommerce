@extends('layouts/contentLayoutMaster')
{{-- @extends('backend.layouts.app') --}}


 @section('page-style')
 <link rel="stylesheet" href="{{ asset('public/css/custom/upload.css') }}" />
@endsection

@section('content')
<div class="aiz-titlebar text-left mt-1 mb-1">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All uploaded files')}}</h1>
		</div>
		<div class="col-md-6 text-right" style="text-align: right;">
			<a href="{{ route('uploaded-files.create') }}" class="btn btn-primary">
				<span>{{translate('Upload New File')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <form id="sort_uploads" action="">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('All files')}}</h5>
            </div>
            <div class="col-md-3 ml-auto mr-0">
                <select class="form-control " name="sort" onchange="sort_uploads()">
                    <option value="newest" @if($sort_by == 'newest') selected="" @endif>{{ translate('Sort by newest') }}</option>
                    <option value="oldest" @if($sort_by == 'oldest') selected="" @endif>{{ translate('Sort by oldest') }}</option>
                    <option value="smallest" @if($sort_by == 'smallest') selected="" @endif>{{ translate('Sort by smallest') }}</option>
                    <option value="largest" @if($sort_by == 'largest') selected="" @endif>{{ translate('Sort by largest') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-xs" name="search" placeholder="{{ translate('Search your files') }}" value="{{ $search }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">{{ translate('Search') }}</button>
            </div>
        </div>
    </form>
    <div class="card-body">
    	<div class="row gutters-5">
    		@foreach($all_uploads as $key => $file)
    			@php
    				if($file->file_original_name == null){
    				    $file_name = translate('Unknown');
    				}else{
    					$file_name = $file->file_original_name;
	    			}
    			@endphp
    			<div class="col-auto w-140px w-lg-220px">
    				<div class="aiz-file-box">
    					<div class="dropdown-file" >
    						<a class="dropdown-link" data-toggle="dropdown">
    							<i data-feather='more-vertical'></i>
    						</a>
    						<div class="dropdown-menu dropdown-menu-right">
    							<a href="javascript:void(0)" class="dropdown-item" onclick="detailsInfo(this)" data-id="{{ $file->id }}">
    								<i data-feather='alert-circle'></i>
    								<span>{{ translate('Details Info') }}</span>
    							</a>
    							<a href="{{ my_asset($file->file_name) }}" target="_blank" download="{{ $file_name }}.{{ $file->extension }}" class="dropdown-item">
                                    <i data-feather='arrow-down'></i>
    								<span>{{ translate('Download') }}</span>
    							</a>
    							<a href="javascript:void(0)" class="dropdown-item" onclick="copyUrl(this)" data-url="{{ my_asset($file->file_name) }}">
    								<i data-feather='clipboard'></i>
    								<span>{{ translate('Copy Link') }}</span>
    							</a>
    							<a href="javascript:void(0)" class="dropdown-item confirm-alert" data-href="{{ route('uploaded-files.destroy', $file->id ) }}" data-target="#delete-modal">
    								<i data-feather='trash-2'></i>
    								<span>{{ translate('Delete') }}</span>
    							</a>
    						</div>
    					</div>
    					<div class="card card-file aiz-uploader-select c-default" title="{{ $file_name }}.{{ $file->extension }}">
    						<div class="card-file-thumb">
    							@if($file->type == 'image')
    								<img src="{{ my_asset($file->file_name) }}" class="img-fit">
    							@elseif($file->type == 'video')
    								<i class="las la-file-video"></i>
    							@else
    								<i class="las la-file"></i>
    							@endif
    						</div>
    						<div class="card-body">
    							<h6 class="d-flex">
    								<span class="text-truncate title">{{ $file_name }}</span>
    								<span class="ext">.{{ $file->extension }}</span>
    							</h6>
    							<p>{{ formatBytes($file->file_size) }}</p>
    						</div>
    					</div>
    				</div>
    			</div>
    		@endforeach
    	</div>
		<div class="aiz-pagination mt-3">
			{{ $all_uploads->appends(request()->input())->links() }}
		</div>
    </div>
</div>
@endsection
@section('modal')
<div id="delete-modal" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true" ></button>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1">{{ translate('Are you sure to delete this file?') }}</p>
                <button type="button" class="btn btn-danger mt-2" data-bs-dismiss="modal">{{ translate('Cancel') }}</button>
                <a href="" class="btn btn-primary mt-2 comfirm-link">{{ translate('Delete') }}</a>
            </div>
        </div>
    </div>
</div>
<div id="info-modal" class="modal fade">
	<div class="modal-dialog modal-dialog-right" style="margin:0 2.44% 0 0;">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title h6">{{ translate('File Info') }}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" style="margin: -0.1rem 1.2rem -0.4rem auto;">
				</button>
			</div>
			<div class="modal-body c-scrollbar-light position-relative" id="info-modal-content">
				<div class="c-preloader text-center absolute-center">
                    <i class="las la-spinner la-spin la-3x opacity-70"></i>
                </div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
	<script type="text/javascript">
		function detailsInfo(e){
            $('#info-modal-content').html('<div class="c-preloader text-center absolute-center"><i class="las la-spinner la-spin la-3x opacity-70"></i></div>');
			var id = $(e).data('id')
			$('#info-modal').modal('show');
			$.post('{{ route('uploaded-files.info') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('#info-modal-content').html(data);
				// console.log(data);
			});
		}
		function copyUrl(e) {
			var url = $(e).data('url');
			var $temp = $("<input>");
		    $("body").append($temp);
		    $temp.val(url).select();
		    try {
			    document.execCommand("copy");
			    AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
			} catch (err) {
			    AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
			}
		    $temp.remove();
		}
        function sort_uploads(el){
            $('#sort_uploads').submit();
        }
	</script>
@endsection
