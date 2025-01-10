@php
    $content = $data->content;
    $serviceList = \App\Models\Services::getAllWithCache($content->limit_list->value);
@endphp
<section class="service-area pt-120 pb-120">
    <div class="service__shape wow slideInRight">
        <img class="sway_Y__animation" src="{{ asset($content->shape->value) }}" alt="shape">
    </div>
    <div class="container">
        <div class="d-flex flex-wrap gap-4 align-items-center justify-content-between mb-60">
            <div class="section-header">
                <h5 class="wow fadeInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
                    <img class="me-1" src="{{ asset($content->title_icon->value) }}" alt="icon">
                    {{ $content->sub_heading->value }}
                </h5>
                <h2 class="wow fadeInLeft" data-wow-delay="200ms"
                    data-wow-duration="1500ms"> {{ $content->heading->value }}</h2>
            </div>
            <a href="{{ $content->button_link->value }}" class="btn-one wow fadeInUp" data-wow-delay="200ms"
               data-wow-duration="1500ms">{{ $content->button_title->value }} <i
                        class="fa-regular fa-arrow-right-long"></i></a>
        </div>
        <div class="row g-4">
            @foreach($serviceList as $service)
                <div class="col-lg-4 col-md-6 wow bounceInUp" data-wow-delay="00ms" data-wow-duration="1000ms">
                    <div class="service__item">
                        <div class="service-shape">
                            <img src="{{ asset('general/static/shape/service-item-shape.png') }}" alt="shape">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="service__icon">
                                <img src="{{ asset($service->dark_icon) }}" alt="icon">
                            </div>
                            <span class="bg-gradient p-1 mt-2 mb-0 rounded text-end ">{{ __('Price: '). setting('currency_symbol').$service->price }}</span>
                        </div>
                        <h4>
                            <a href="{{ route('service.details', ['slug' => $service->slug]) }}">{{ _tr($service->name) }}</a>
                        </h4>
                        <p>{{ \Str::limit(filterRichText(_tr($service->description)), $content->limit_summary->value) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
