@extends('layouts.app', ['page_title' => (!empty($current_user) ? 'Accueil' : 'Bienvenue sur Addrressimmo')])

@section('app-content')

        <div class="hero">
            <div class="hero-slide">
                <div class="img overlay" style="background-image: url('images/hero_bg_3.jpg')"></div>
                <div class="img overlay" style="background-image: url('images/hero_bg_2.jpg')"></div>
                <div class="img overlay" style="background-image: url('images/hero_bg_1.jpg')"></div>
            </div>

            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-8 text-center">
                        <h1 class="heading" data-aos="fade-up">
                            Commencez ici
                        </h1>
                        <div id="main-search" class="card card-body">
                            <!-- Tabs navs -->
                            <ul class="nav nav-tabs nav-fill mb-3 d-flex flex-nowrap" id="ex1" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a data-mdb-tab-init class="nav-link bg-transparent active" id="ex2-tab-1" href="#ex2-tabs-1" role="tab" aria-controls="ex2-tabs-1" aria-selected="true" >
                                        <i class="bi bi-handbag me-sm-2 fs-6 align-middle"></i><span class="d-sm-inline d-block mt-2">Acheter</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a data-mdb-tab-init class="nav-link bg-transparent" id="ex2-tab-2" href="#ex2-tabs-2" role="tab" aria-controls="ex2-tabs-2" aria-selected="false" >
                                        <i class="bi bi-clock me-sm-2 fs-6 align-middle"></i><span class="d-sm-inline d-block mt-2">Louer</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a data-mdb-tab-init class="nav-link bg-transparent" id="ex2-tab-3" href="#ex2-tabs-3" role="tab" aria-controls="ex2-tabs-3" aria-selected="false">
                                        <i class="bi bi-cash-coin me-sm-2 fs-6 align-middle"></i><span class="d-sm-inline d-block mt-2">Vendre</span>
                                    </a>
                                </li>
                            </ul>
                            <!-- Tabs navs -->

                            <!-- Tabs content -->
                            <div class="tab-content" id="ex2-content">
                                <div class="tab-pane fade show active" id="ex2-tabs-1" role="tabpanel" aria-labelledby="ex2-tab-1">
                                    <form action="#" class="form-search d-flex align-items-stretch my-2 border border-success rounded-pill" data-aos="fade-up" data-aos-delay="200">
                                        <div class="input-group">
                                            <div class="input-group-text border-0 me-0 pe-1 py-4">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <input type="text" class="form-control px-3 py-4" placeholder="Saisir adresse entière, commune ou quartier" />
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="ex2-tabs-2" role="tabpanel" aria-labelledby="ex2-tab-2">
                                    <form action="#" class="form-search d-flex align-items-stretch my-2 border border-success rounded-pill" data-aos="fade-up" data-aos-delay="200">
                                        <div class="input-group">
                                            <div class="input-group-text border-0 me-0 pe-1 py-4">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <input type="text" class="form-control px-3 py-4" placeholder="Saisir adresse entière, commune ou quartier" />
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="ex2-tabs-3" role="tabpanel" aria-labelledby="ex2-tab-3">
                                    <form action="#" class="form-search d-sm-flex justify-content-center align-items-center pt-3 py-2" data-aos="fade-up" data-aos-delay="200">
                                        <div class="form-check form-check-inline mt-sm-0 mt-2">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked/>
                                            <label class="form-check-label" for="inlineRadio1">Parcelle</label>
                                        </div>

                                        <div class="form-check form-check-inline mt-sm-0 mt-2">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option1" />
                                            <label class="form-check-label" for="inlineRadio1">Maison</label>
                                        </div>

                                        <div class="form-check form-check-inline mt-sm-0 mt-2">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option1" />
                                            <label class="form-check-label" for="inlineRadio1">Appartement</label>
                                        </div>

                                        <div class="form-check form-check-inline mt-sm-0 mt-2">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="option2" />
                                            <label class="form-check-label" for="inlineRadio2">Equipement</label>
                                        </div>

                                        <button type="button" class="btn adrm-btn-green mt-sm-0 mt-2 rounded-pill">Commencer</button>
                                    </form>
                                </div>
                            </div>
                            <!-- Tabs content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="container">
                <div class="row mb-5 align-items-center">
                    <div class="col-lg-6">
                        <h2 class="font-weight-bold text-primary heading">
                            Propriétés récentes
                        </h2>
                    </div>
                    <div class="col-lg-6 text-lg-end">
                        <p>
                            <a href="#" target="_blank" class="btn adrm-btn-green text-white py-3 px-4">
                                Voir tout<i class="bi bi-chevron-double-right ms-2"></i>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="property-slider-wrap">
                            <div class="property-slider">
                                <div class="property-item">
                                    <a href="property-single.html" class="img">
                                        <img src="images/img_1.jpg" alt="Image" class="img-fluid" />
                                    </a>

                                    <div class="property-content">
                                        <div class="price mb-2"><span>$1,291,000</span></div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">5232 Kinshasa Fake, Ave. 21BC</span>
                                            <span class="city d-block mb-3">Kinshasa, RDC</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">2 beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">2 baths</span>
                                                </span>
                                            </div>

                                            <a href="property-single.html" class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->

                                <div class="property-item">
                                    <a href="property-single.html" class="img">
                                        <img src="images/img_2.jpg" alt="Image" class="img-fluid" />
                                    </a>

                                    <div class="property-content">
                                        <div class="price mb-2"><span>$1,291,000</span></div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">5232 Kinshasa Fake, Ave. 21BC</span>
                                            <span class="city d-block mb-3">Kinshasa, RDC</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">2 beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">2 baths</span>
                                                </span>
                                            </div>

                                            <a href="property-single.html" class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->

                                <div class="property-item">
                                    <a href="property-single.html" class="img">
                                        <img src="images/img_3.jpg" alt="Image" class="img-fluid" />
                                    </a>

                                    <div class="property-content">
                                        <div class="price mb-2"><span>$1,291,000</span></div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">5232 Kinshasa Fake, Ave. 21BC</span>
                                            <span class="city d-block mb-3">Kinshasa, RDC</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">2 beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">2 baths</span>
                                                </span>
                                            </div>

                                            <a href="property-single.html" class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->

                                <div class="property-item">
                                    <a href="property-single.html" class="img">
                                        <img src="images/img_4.jpg" alt="Image" class="img-fluid" />
                                    </a>

                                    <div class="property-content">
                                        <div class="price mb-2"><span>$1,291,000</span></div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">5232 Kinshasa Fake, Ave. 21BC</span>
                                            <span class="city d-block mb-3">Kinshasa, RDC</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">2 beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">2 baths</span>
                                                </span>
                                            </div>

                                            <a href="property-single.html" class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->

                                <div class="property-item">
                                    <a href="property-single.html" class="img">
                                        <img src="images/img_5.jpg" alt="Image" class="img-fluid" />
                                    </a>

                                    <div class="property-content">
                                        <div class="price mb-2"><span>$1,291,000</span></div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">5232 Kinshasa Fake, Ave. 21BC</span>
                                            <span class="city d-block mb-3">Kinshasa, RDC</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">2 beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">2 baths</span>
                                                </span>
                                            </div>

                                            <a href="property-single.html" class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->

                                <div class="property-item">
                                    <a href="property-single.html" class="img">
                                        <img src="images/img_6.jpg" alt="Image" class="img-fluid" />
                                    </a>

                                    <div class="property-content">
                                        <div class="price mb-2"><span>$1,291,000</span></div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">5232 Kinshasa Fake, Ave. 21BC</span>
                                            <span class="city d-block mb-3">Kinshasa, RDC</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">2 beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">2 baths</span>
                                                </span>
                                            </div>

                                            <a href="property-single.html" class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->

                                <div class="property-item">
                                    <a href="property-single.html" class="img">
                                        <img src="images/img_7.jpg" alt="Image" class="img-fluid" />
                                    </a>

                                    <div class="property-content">
                                        <div class="price mb-2"><span>$1,291,000</span></div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">5232 Kinshasa Fake, Ave. 21BC</span>
                                            <span class="city d-block mb-3">Kinshasa, RDC</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">2 beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">2 baths</span>
                                                </span>
                                            </div>

                                            <a href="property-single.html" class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->

                                <div class="property-item">
                                    <a href="property-single.html" class="img">
                                        <img src="images/img_8.jpg" alt="Image" class="img-fluid" />
                                    </a>

                                    <div class="property-content">
                                        <div class="price mb-2"><span>$1,291,000</span></div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">5232 Kinshasa Fake, Ave. 21BC</span>
                                            <span class="city d-block mb-3">Kinshasa, RDC</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">2 beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">2 baths</span>
                                                </span>
                                            </div>

                                            <a href="property-single.html" class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->

                                <div class="property-item">
                                    <a href="property-single.html" class="img">
                                        <img src="images/img_1.jpg" alt="Image" class="img-fluid" />
                                    </a>

                                    <div class="property-content">
                                        <div class="price mb-2"><span>$1,291,000</span></div>
                                        <div>
                                            <span class="d-block mb-2 text-black-50">5232 Kinshasa Fake, Ave. 21BC</span>
                                            <span class="city d-block mb-3">Kinshasa, RDC</span>

                                            <div class="specs d-flex mb-4">
                                                <span class="d-block d-flex align-items-center me-3">
                                                    <span class="icon-bed me-2"></span>
                                                    <span class="caption">2 beds</span>
                                                </span>
                                                <span class="d-block d-flex align-items-center">
                                                    <span class="icon-bath me-2"></span>
                                                    <span class="caption">2 baths</span>
                                                </span>
                                            </div>

                                            <a href="property-single.html" class="btn adrm-btn-red py-2 px-3 rounded-pill">Voir détails</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- .item -->
                            </div>

                            <div id="property-nav" class="controls" tabindex="0" aria-label="Carousel Navigation">
                                <span class="prev" data-controls="prev" aria-controls="property"
                                    tabindex="-1">Prev</span>
                                <span class="next" data-controls="next" aria-controls="property"
                                    tabindex="-1">Next</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="features-1">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="box-feature">
                            <span class="flaticon-house"></span>
                            <h3 class="mb-3">Our Properties</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Voluptates, accRDCmus.
                            </p>
                            <p><a href="#" class="learn-more">Learn More</a></p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="500">
                        <div class="box-feature">
                            <span class="flaticon-building"></span>
                            <h3 class="mb-3">Property for Sale</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Voluptates, accRDCmus.
                            </p>
                            <p><a href="#" class="learn-more">Learn More</a></p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                        <div class="box-feature">
                            <span class="flaticon-house-3"></span>
                            <h3 class="mb-3">Real Estate Agent</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Voluptates, accRDCmus.
                            </p>
                            <p><a href="#" class="learn-more">Learn More</a></p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="600">
                        <div class="box-feature">
                            <span class="flaticon-house-1"></span>
                            <h3 class="mb-3">House for Sale</h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Voluptates, accRDCmus.
                            </p>
                            <p><a href="#" class="learn-more">Learn More</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="section sec-testimonials">
            <div class="container">
                <div class="row mb-5 align-items-center">
                    <div class="col-md-6">
                        <h2 class="font-weight-bold heading text-primary mb-4 mb-md-0">
                            Customer Says
                        </h2>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div id="testimonial-nav">
                            <span class="prev" data-controls="prev">Prev</span>

                            <span class="next" data-controls="next">Next</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4"></div>
                </div>
                <div class="testimonial-slider-wrap">
                    <div class="testimonial-slider">
                        <div class="item">
                            <div class="testimonial">
                                <img src="images/person_1-min.jpg" alt="Image"
                                    class="img-fluid rounded-circle w-25 mb-4" />
                                <div class="rate">
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                </div>
                                <h3 class="h5 text-primary mb-4">James Smith</h3>
                                <blockquote>
                                    <p>
                                        &ldquo;Far far away, behind the word mountains, far from the
                                        countries Vokalia and Consonantia, there live the blind
                                        texts. Separated they live in Bookmarksgrove right at the
                                        coast of the Semantics, a large language ocean.&rdquo;
                                    </p>
                                </blockquote>
                                <p class="text-black-50">Designer, Co-founder</p>
                            </div>
                        </div>

                        <div class="item">
                            <div class="testimonial">
                                <img src="images/person_2-min.jpg" alt="Image"
                                    class="img-fluid rounded-circle w-25 mb-4" />
                                <div class="rate">
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                </div>
                                <h3 class="h5 text-primary mb-4">Mike Houston</h3>
                                <blockquote>
                                    <p>
                                        &ldquo;Far far away, behind the word mountains, far from the
                                        countries Vokalia and Consonantia, there live the blind
                                        texts. Separated they live in Bookmarksgrove right at the
                                        coast of the Semantics, a large language ocean.&rdquo;
                                    </p>
                                </blockquote>
                                <p class="text-black-50">Designer, Co-founder</p>
                            </div>
                        </div>

                        <div class="item">
                            <div class="testimonial">
                                <img src="images/person_3-min.jpg" alt="Image"
                                    class="img-fluid rounded-circle w-25 mb-4" />
                                <div class="rate">
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                </div>
                                <h3 class="h5 text-primary mb-4">Cameron Webster</h3>
                                <blockquote>
                                    <p>
                                        &ldquo;Far far away, behind the word mountains, far from the
                                        countries Vokalia and Consonantia, there live the blind
                                        texts. Separated they live in Bookmarksgrove right at the
                                        coast of the Semantics, a large language ocean.&rdquo;
                                    </p>
                                </blockquote>
                                <p class="text-black-50">Designer, Co-founder</p>
                            </div>
                        </div>

                        <div class="item">
                            <div class="testimonial">
                                <img src="images/person_4-min.jpg" alt="Image"
                                    class="img-fluid rounded-circle w-25 mb-4" />
                                <div class="rate">
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                    <span class="icon-star text-warning"></span>
                                </div>
                                <h3 class="h5 text-primary mb-4">Dave Smith</h3>
                                <blockquote>
                                    <p>
                                        &ldquo;Far far away, behind the word mountains, far from the
                                        countries Vokalia and Consonantia, there live the blind
                                        texts. Separated they live in Bookmarksgrove right at the
                                        coast of the Semantics, a large language ocean.&rdquo;
                                    </p>
                                </blockquote>
                                <p class="text-black-50">Designer, Co-founder</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section section-4 bg-light">
            <div class="container">
                <div class="row justify-content-center text-center mb-5">
                    <div class="col-lg-5">
                        <h2 class="font-weight-bold heading text-primary mb-4">
                            Let's find home that's perfect for you
                        </h2>
                        <p class="text-black-50">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam
                            enim pariatur similique debitis vel nisi qui reprehenderit.
                        </p>
                    </div>
                </div>
                <div class="row justify-content-between mb-5">
                    <div class="col-lg-7 mb-5 mb-lg-0 order-lg-2">
                        <div class="img-about dots">
                            <img src="images/hero_bg_3.jpg" alt="Image" class="img-fluid" />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex feature-h">
                            <span class="wrap-icon me-3">
                                <span class="icon-home2"></span>
                            </span>
                            <div class="feature-text">
                                <h3 class="heading">2M Properties</h3>
                                <p class="text-black-50">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Nostrum iste.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex feature-h">
                            <span class="wrap-icon me-3">
                                <span class="icon-person"></span>
                            </span>
                            <div class="feature-text">
                                <h3 class="heading">Top Rated Agents</h3>
                                <p class="text-black-50">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Nostrum iste.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex feature-h">
                            <span class="wrap-icon me-3">
                                <span class="icon-security"></span>
                            </span>
                            <div class="feature-text">
                                <h3 class="heading">Legit Properties</h3>
                                <p class="text-black-50">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Nostrum iste.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row section-counter mt-5">
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="counter-wrap mb-5 mb-lg-0">
                            <span class="number"><span class="countup text-primary">3298</span></span>
                            <span class="caption text-black-50"># of Buy Properties</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                        <div class="counter-wrap mb-5 mb-lg-0">
                            <span class="number"><span class="countup text-primary">2181</span></span>
                            <span class="caption text-black-50"># of Sell Properties</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="500">
                        <div class="counter-wrap mb-5 mb-lg-0">
                            <span class="number"><span class="countup text-primary">9316</span></span>
                            <span class="caption text-black-50"># of All Properties</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="600">
                        <div class="counter-wrap mb-5 mb-lg-0">
                            <span class="number"><span class="countup text-primary">7191</span></span>
                            <span class="caption text-black-50"># of Agents</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="row justify-content-center footer-cta" data-aos="fade-up">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="mb-4">Be a part of our growing real state agents</h2>
                    <p>
                        <a href="#" target="_blank" class="btn adrm-btn-green text-white py-3 px-4">Apply for Real
                            Estate agent</a>
                    </p>
                </div>
                <!-- /.col-lg-7 -->
            </div>
            <!-- /.row -->
        </div>

        <div class="section section-5 bg-light">
            <div class="container">
                <div class="row justify-content-center text-center mb-5">
                    <div class="col-lg-6 mb-5">
                        <h2 class="font-weight-bold heading text-primary mb-4">
                            Our Agents
                        </h2>
                        <p class="text-black-50">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam
                            enim pariatur similique debitis vel nisi qui reprehenderit totam?
                            Quod maiores.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
                        <div class="h-100 person">
                            <img src="images/person_1-min.jpg" alt="Image" class="img-fluid" />

                            <div class="person-contents">
                                <h2 class="mb-0"><a href="#">James Doe</a></h2>
                                <span class="meta d-block mb-3">Real Estate Agent</span>
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Facere officiis inventore cumque tenetur laboriosam, minus
                                    culpa doloremque odio, neque molestias?
                                </p>

                                <ul class="social list-unstyled list-inline dark-hover">
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-instagram"></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
                        <div class="h-100 person">
                            <img src="images/person_2-min.jpg" alt="Image" class="img-fluid" />

                            <div class="person-contents">
                                <h2 class="mb-0"><a href="#">Jean Smith</a></h2>
                                <span class="meta d-block mb-3">Real Estate Agent</span>
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Facere officiis inventore cumque tenetur laboriosam, minus
                                    culpa doloremque odio, neque molestias?
                                </p>

                                <ul class="social list-unstyled list-inline dark-hover">
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-instagram"></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
                        <div class="h-100 person">
                            <img src="images/person_3-min.jpg" alt="Image" class="img-fluid" />

                            <div class="person-contents">
                                <h2 class="mb-0"><a href="#">Alicia Huston</a></h2>
                                <span class="meta d-block mb-3">Real Estate Agent</span>
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Facere officiis inventore cumque tenetur laboriosam, minus
                                    culpa doloremque odio, neque molestias?
                                </p>

                                <ul class="social list-unstyled list-inline dark-hover">
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-twitter"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-facebook"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-linkedin"></span></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#"><span class="icon-instagram"></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
