<script>
    function heroCarousel() {
        return {
            currentSlide: 0,
            autoSlideInterval: null,
            showControls: false,
            hideControlsTimeout: null,

            init() {
                this.startAutoSlide();
                this.setupTouchEvents();
            },

            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % 3;
                this.resetAutoSlide();
                this.showControlsTemporarily();
            },

            prevSlide() {
                this.currentSlide = this.currentSlide === 0 ? 2 : this.currentSlide - 1;
                this.resetAutoSlide();
                this.showControlsTemporarily();
            },

            goToSlide(index) {
                this.currentSlide = index;
                this.resetAutoSlide();
                this.showControlsTemporarily();
            },

            startAutoSlide() {
                this.autoSlideInterval = setInterval(() => {
                    this.nextSlide();
                }, 5000);
            },

            resetAutoSlide() {
                if (this.autoSlideInterval) {
                    clearInterval(this.autoSlideInterval);
                }
                this.startAutoSlide();
            },

            showControlsTemporarily() {
                this.showControls = true;
                if (this.hideControlsTimeout) {
                    clearTimeout(this.hideControlsTimeout);
                }
                this.hideControlsTimeout = setTimeout(() => {
                    this.showControls = false;
                }, 3000);
            },

            setupTouchEvents() {
                let startX = 0;
                let startY = 0;

                this.$el.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                    this.showControlsTemporarily();
                });

                this.$el.addEventListener('touchend', (e) => {
                    if (!startX || !startY) return;

                    let endX = e.changedTouches[0].clientX;
                    let endY = e.changedTouches[0].clientY;

                    let diffX = startX - endX;
                    let diffY = startY - endY;

                    // Check if horizontal swipe is more significant than vertical
                    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                        if (diffX > 0) {
                            this.nextSlide();
                        } else {
                            this.prevSlide();
                        }
                    }

                    startX = 0;
                    startY = 0;
                });
            },
        };
    }
</script>
