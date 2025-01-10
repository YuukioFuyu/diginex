@extends('backend.layouts.app')
@section('title')
	{{ __('App Info') }}
@endsection
@section('content')
	<div class="py-4">
		<div class="d-flex justify-content-between w-100 flex-wrap">
			<div class="mb-3 mb-lg-0">
				<h1 class="h4">{{  __('App Info') }}</h1>
			</div>
		
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-xl-12">
			<div class="card card-body border-0 shadow mb-4">
				
				<div class="table-responsive">
					<table class="table table-centered table-nowrap mb-0 rounded">
						<tbody>
						@foreach($data as $name => $value)
							<tr>
								<td class="border-0 fw-bold"><img class="image-xs " src="{{ asset('general/static/tech/'.str_replace('_version','',$name).'.png') }}" alt=""> <span
										class="mx-2"></span> {{ucwords( str_replace('_',' ',$name)) }}</td>
								<td class="border-0 text-success">
									<div class="d-flex align-items-center">
										<span class="fw-bold">{{$value}}</span></div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

@endsection