    <div style=" max-width: 600px; position: relative; margin: 0 auto;">


        <div x-data="{
            initSwiper: function() {
                new Swiper('.swiper-container', {
                    loop: true,
                    slidesPerView: 1,
                    spaceBetween: 30,
                    centeredSlides: true,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    observer: true,
                    observeParents: true,
                });
            }
        }" x-init="
            if (typeof Swiper === 'undefined') {
                const swiperCSS = document.createElement('link');
                swiperCSS.rel = 'stylesheet';
                swiperCSS.href = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css';
                document.head.appendChild(swiperCSS);

                const swiperScript = document.createElement('script');
                swiperScript.src = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js';
                swiperScript.onload = () => initSwiper();
                document.head.appendChild(swiperScript);
            } else {
                initSwiper();
            }
        ">
            <div class="swiper-container" style="height: 300px; width: 100%; overflow: hidden;">
                <div class="swiper-wrapper">
                    @foreach ($record->images as $image)
                    <div class="swiper-slide"><img src="{{ asset('storage/' . $image) }}" alt="Слайд 1"
                            style="width: 100%; border-radius: 100px;height: 100%; object-fit: contain;"></div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>