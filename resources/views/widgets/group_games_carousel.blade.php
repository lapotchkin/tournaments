<div id="carousel-games" class="carousel slide bg-dark text-white mb-3" data-ride="carousel">
    <div class="carousel-inner row w-100 mx-auto" role="listbox">
        <div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 active border-light border-right"
             style="height: 6rem">
            Игра 1<br>
            nhfkfkf
        </div>
        <div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 border-light border-right" style="height: 6rem">
            2
        </div>
        <div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 border-light border-right" style="height: 6rem">
            3
        </div>
        <div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 border-light border-right" style="height: 6rem">
            4
        </div>
        <div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 border-light border-right" style="height: 6rem">
            5
        </div>
        <div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 border-light border-right" style="height: 6rem">
            6
        </div>
        <div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 border-light border-right" style="height: 6rem">
            7
        </div>
        <div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 border-light border-right" style="height: 6rem">
            8
        </div>
    </div>
    <a class="carousel-control-prev" href="#carousel-games" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel-games" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<script>
    /*
    Carousel
*/
    $('#carousel-games').on('slide.bs.carousel', function (e) {
        /*
            CC 2.0 License Iatek LLC 2018 - Attribution required
        */
        const $e = $(e.relatedTarget);
        const idx = $e.index();
        const itemsPerSlide = 5;
        const $items = $('.carousel-item');
        const totalItems = $items.length;

        if (idx >= totalItems - (itemsPerSlide - 1)) {
            const it = itemsPerSlide - (totalItems - idx);
            for (let i = 0; i < it; i++) {
                // append slides to end
                if (e.direction === "left") {
                    $items.eq(i).appendTo('.carousel-inner');
                } else {
                    $items.eq(0).appendTo('.carousel-inner');
                }
            }
        }
    });
</script>
