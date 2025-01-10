@php
    $content = $data->content;
    $serviceList = \App\Models\Services::getAll();
@endphp

<section class="service-two-area secondary-bg pt-120 pb-120">
    <div class="service-two__shape-left sway_Y__animationY">
        <img src="{{ asset($content->shape_left->value) }}" alt="shape">
    </div>
    <div class="service-two__shape-right sway_Y__animation">
        <img src="{{ asset($content->shape_right->value) }}" alt="shape">
    </div>
    <div class="container">
        <div class="d-flex gap-4 flex-wrap align-items-center justify-content-between mb-60">
            <div class="section-header">
                <h5 class="wow fadeInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">

                    <x-icon name="header-icon"/>

                    {{ $content->sub_heading->value }}
                </h5>
                <h2 class="text-white wow fadeInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">
                    {{ $content->heading->value }} </h2>
            </div>
            <div class="arry-btn  d-flex gap-3 wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1500ms">
                <button class="arry-prev service__arry-prev"><i class="fa-light fa-chevron-left"></i></button>
                <button class="arry-next service__arry-next active"><i
                            class="fa-light fa-chevron-right"></i></button>
            </div>
        </div>
        <div class="swiper service-two__slider">
            <div class="swiper-wrapper">

                @foreach($serviceList as $service)
                    <div class="swiper-slide">
                        <div class="service-two__item">
                            <div class="image">
                                <img class="component-blog-cover" src="{{ asset($service->cover) }}"
                                     alt="image">
                            </div>
                            <div class="service-two__content">
                                <div class="icon">
                                    <img src="{{ asset($service->light_icon) }}" alt="icon">
                                </div>
                                <div class="shape"><img src="{{ asset('general/static/shape/service-two-item-shape.png') }}"
                                                        alt="shape"></div>
                                <h4>
                                    <a href="{{ route('service.details', ['slug' => $service->slug]) }}"
                                       class="primary-hover">{{ _tr($service->name) }}</a></h4>
                                <p>{{ \Str::limit(filterRichText(_tr($service->description)), $content->limit_summary->value) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a class="read-more-btn" href="{{ route('service.details', ['slug' => $service->slug]) }}">{{ __('Read More') }} <i class="fa-regular fa-arrow-right-long"></i></a>
                                    <span class="read-more-btn text-end ">{{ __('Price: '). setting('currency_symbol').$service->price }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
