<div id="targetElement" class="sidebar-area sidebar__hide light-area">
    <div class="sidebar__overlay"></div>


    <a href="{{ route('home') }}" class="logo mb-40">
        <img src="{{ asset(setting('light_logo')) }}" alt="logo">
    </a>
    @php $route = auth()->check() ? route('user.dashboard') : route('login'); @endphp
    <a href="{{ $route }}" class="btn-one pt-0 pb-0 rounded">
        {{ auth()->check() ? __('Dashboard') : __('Login') }} <i class="fa-regular fa-arrow-right-long"></i>
    </a>
    <div class="mobile-menu overflow-hidden"></div>

    @if(setting('language_visibility'))
        <div class="light-area mt-20 pb-20">
            <select name="locale" class="light-area"
                    onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                @foreach($languages as $language)
                    <option class="light-area"
                            value="{{ route('locale-set', $language->code) }}" @selected($language->code == app()->getLocale())>{{ $language->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <ul class="info pt-40">
        <li class="py-2"><i class="fa-solid primary-color fa-phone-volume"></i> <a
                    href="tel:{{ trim(explode(',', $contactInfo->content->contact_number->value)[0]) }}">{{ trim(explode(',', $contactInfo->content->contact_number->value)[0]) }}</a>
        </li>
        <li><i class="fa-solid primary-color fa-paper-plane"></i> <a
                    href="mailto:{{ $contactInfo->content->support_mail->value }}">{{ $contactInfo->content->support_mail->value }}</a>
        </li>
    </ul>
    <div class="social-icon mt-20">
        @foreach ($socials as $social)
            <a href="{{ $social->url }}" target="{{ $social->target }}"><i class="{{ $social->icon_class }}"></i></a>
        @endforeach
    </div>
    <button id="closeButton" class="text-white"><i class="fa-solid fa-xmark"></i></button>
</div>
