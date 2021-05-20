<section class="hero-wrapper overflow-hidden bread-bg">
    <div class="overlay"></div><!-- this is the gray background on the home page. Don't delete it. -->

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="hero-heading text-center">
                    <div class="section-heading">
                        <h2 class="sec__title">Want to have your own roof above your head?</h2>
                        <p class="sec__desc">Find your dream flat. Start here.</p>
                    </div>
                </div><!-- end hero-heading -->


                <form class="main-search-input" action="/ad-list/non-processed" method="post">
                    @csrf

                    {{--                        City --}}
                    <div class="main-search-input-item">
                        <div class="form-box">
                            <div class="form-group mb-0">
                                <input
                                    id="city"
                                    name="city"
                                    class="form-control"
                                    type="search"
                                    placeholder="Name your city..."
                                >
                            </div>
                        </div>
                    </div>

                    {{--                        Max price --}}
                    <div class="main-search-input-item">
                        <div class="form-box">
                            <div class="form-group mb-0">
                                <input
                                    id="max_price"
                                    name="max_price"
                                    class="form-control"
                                    type="search"
                                    placeholder="Upper spending limit"
                                >
                            </div>
                        </div>
                    </div>

                    {{--                        Max rooms --}}
                    <div class="main-search-input-item">
                        <div class="form-box">
                            <div class="form-group mb-0">
                                <input
                                    id="max_rooms"
                                    name="max_rooms"
                                    class="form-control"
                                    type="search"
                                    placeholder="Max number of rooms?"
                                >
                            </div>
                        </div>
                    </div>

                    {{--                        Button--}}
                    <div class="main-search-input-item">
                        <button class="theme-btn gradient-btn border-0 w-100" type="submit">Search Now</button>
                    </div>
                </form>

                <div class="highlighted-categories">{{--Do not delete this div. --}}</div>
            </div><!-- end col-lg-12 -->
        </div><!-- end row -->
    </div><!-- end container -->
    <div class="svg-bg">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
            <path fill="#F5F7FC" class="elementor-shape-fill" d="M500,97C126.7,96.3,0.8,19.8,0,0v100l1000,0V1C1000,19.4,873.3,97.8,500,97z"></path>
        </svg>
    </div>
</section>
