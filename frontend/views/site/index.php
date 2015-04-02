<?php
/* @var $this yii\web\View */
$this->title = Yii::$app->name;
?>

<style>

</style>
<!-- Services Section -->
<section id="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">Features</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-shopping-cart fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="service-heading">E-Commerce</h4>
                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
            </div>
            <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-laptop fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="service-heading">Responsive Design</h4>
                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
            </div>
            <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="service-heading">Web Security</h4>
                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
            </div>
        </div>
    </div>
</section>

<!-- Portfolio Grid Section -->
<section id="screenshots" class="bg-light-gray portfolio">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">Screenshots</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 portfolio-item">
                <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                    <div class="portfolio-hover">
                        <div class="portfolio-hover-content">
                            <i class="fa fa-plus fa-3x"></i>
                        </div>
                    </div>
                    <img src="img/portfolio/roundicons.png" class="img-responsive" alt="">
                </a>
                <div class="portfolio-caption">
                    <h4>Dashboard</h4>
                    <p class="text-muted">All in one ...</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 portfolio-item">
                <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                    <div class="portfolio-hover">
                        <div class="portfolio-hover-content">
                            <i class="fa fa-plus fa-3x"></i>
                        </div>
                    </div>
                    <img src="img/portfolio/startup-framework.png" class="img-responsive" alt="">
                </a>
                <div class="portfolio-caption">
                    <h4>Domain</h4>
                    <p class="text-muted">Website Design</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 portfolio-item">
                <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                    <div class="portfolio-hover">
                        <div class="portfolio-hover-content">
                            <i class="fa fa-plus fa-3x"></i>
                        </div>
                    </div>
                    <img src="img/portfolio/treehouse.png" class="img-responsive" alt="">
                </a>
                <div class="portfolio-caption">
                    <h4>Tickets</h4>
                    <p class="text-muted">Website Design</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 portfolio-item">
                <a href="#portfolioModal4" class="portfolio-link" data-toggle="modal">
                    <div class="portfolio-hover">
                        <div class="portfolio-hover-content">
                            <i class="fa fa-plus fa-3x"></i>
                        </div>
                    </div>
                    <img src="img/portfolio/golden.png" class="img-responsive" alt="">
                </a>
                <div class="portfolio-caption">
                    <h4>Client profile</h4>
                    <p class="text-muted">Website Design</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 portfolio-item">
                <a href="#portfolioModal5" class="portfolio-link" data-toggle="modal">
                    <div class="portfolio-hover">
                        <div class="portfolio-hover-content">
                            <i class="fa fa-plus fa-3x"></i>
                        </div>
                    </div>
                    <img src="img/portfolio/escape.png" class="img-responsive" alt="">
                </a>
                <div class="portfolio-caption">
                    <h4>Login</h4>
                    <p class="text-muted">Website Design</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 portfolio-item">
                <a href="#portfolioModal6" class="portfolio-link" data-toggle="modal">
                    <div class="portfolio-hover">
                        <div class="portfolio-hover-content">
                            <i class="fa fa-plus fa-3x"></i>
                        </div>
                    </div>
                    <img src="img/portfolio/dreams.png" class="img-responsive" alt="">
                </a>
                <div class="portfolio-caption">
                    <h4>Servers</h4>
                    <p class="text-muted">Website Design</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="pricing">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">Pricing</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
        </div>
        <div class="row">
            <!-- Pricing -->
            <div class="col-md-4">
                <div class="pricing hover-effect">
                    <div class="pricing-head">
                        <h3>Begginer <span>
											Officia deserunt mollitia </span>
                        </h3>
                        <h4><i>$</i>5<i>.49</i>
											<span>
											Per Month </span>
                        </h4>
                    </div>
                    <ul class="pricing-content list-unstyled">
                        <li>
                            <i class="fa fa-tags"></i> At vero eos
                        </li>
                        <li>
                            <i class="fa fa-asterisk"></i> No Support
                        </li>
                        <li>
                            <i class="fa fa-heart"></i> Fusce condimentum
                        </li>
                        <li>
                            <i class="fa fa-star"></i> Ut non libero
                        </li>
                        <li>
                            <i class="fa fa-shopping-cart"></i> Consecte adiping elit
                        </li>
                    </ul>
                    <div class="pricing-footer">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut non libero magna psum olor .
                        </p>
                        <a href="#" class="btn btn-default">
                            Sign Up <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pricing pricing-active hover-effect">
                    <div class="pricing-head pricing-head-active">
                        <h3>Expert <span>
											Officia deserunt mollitia </span>
                        </h3>
                        <h4><i>$</i>13<i>.99</i>
											<span>
											Per Month </span>
                        </h4>
                    </div>
                    <ul class="pricing-content list-unstyled">
                        <li>
                            <i class="fa fa-tags"></i> At vero eos
                        </li>
                        <li>
                            <i class="fa fa-asterisk"></i> No Support
                        </li>
                        <li>
                            <i class="fa fa-heart"></i> Fusce condimentum
                        </li>
                        <li>
                            <i class="fa fa-star"></i> Ut non libero
                        </li>
                        <li>
                            <i class="fa fa-shopping-cart"></i> Consecte adiping elit
                        </li>
                    </ul>
                    <div class="pricing-footer">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut non libero magna psum olor .
                        </p>
                        <a href="#" class="btn btn-default">
                            Sign Up <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pricing hover-effect">
                    <div class="pricing-head">
                        <h3>Hi-Tech <span>
											Officia deserunt mollitia </span>
                        </h3>
                        <h4><i>$</i>99<i>.00</i>
											<span>
											Per Month </span>
                        </h4>
                    </div>
                    <ul class="pricing-content list-unstyled">
                        <li>
                            <i class="fa fa-tags"></i> At vero eos
                        </li>
                        <li>
                            <i class="fa fa-asterisk"></i> No Support
                        </li>
                        <li>
                            <i class="fa fa-heart"></i> Fusce condimentum
                        </li>
                        <li>
                            <i class="fa fa-star"></i> Ut non libero
                        </li>
                        <li>
                            <i class="fa fa-shopping-cart"></i> Consecte adiping elit
                        </li>
                    </ul>
                    <div class="pricing-footer">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut non libero magna psum olor .
                        </p>
                        <a href="#" class="btn btn-default">
                            Sign Up <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!--//End Pricing -->
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="bg-light-gray">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">About</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <ul class="timeline">
                    <li>
                        <div class="timeline-image">
                            <img class="img-circle img-responsive" src="img/about/1.jpg" alt="">
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>NOVEMBER 2014</h4>
                                <h4 class="subheading">Our Humble Beginnings</h4>
                            </div>
                            <div class="timeline-body">
                                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                            </div>
                        </div>
                    </li>
                    <li class="timeline-inverted">
                        <div class="timeline-image">
                            <img class="img-circle img-responsive" src="img/about/2.jpg" alt="">
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>DECEMBER 2014</h4>
                                <h4 class="subheading">An Agency is Born</h4>
                            </div>
                            <div class="timeline-body">
                                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="timeline-image">
                            <img class="img-circle img-responsive" src="img/about/3.jpg" alt="">
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>FEBRUARY 2015</h4>
                                <h4 class="subheading">Transition to Full Service</h4>
                            </div>
                            <div class="timeline-body">
                                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                            </div>
                        </div>
                    </li>
                    <li class="timeline-inverted">
                        <div class="timeline-image">
                            <img class="img-circle img-responsive" src="img/about/4.jpg" alt="">
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>MARCH 2015</h4>
                                <h4 class="subheading">Phase Two Expansion</h4>
                            </div>
                            <div class="timeline-body">
                                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                            </div>
                        </div>
                    </li>
                    <li class="timeline-inverted">
                        <div class="timeline-image">
                            <h4>Be Part
                                <br>Of Our
                                <br>Story!</h4>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section id="team">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">Our Amazing Team</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="team-member">
                    <img src="https://pp.vk.me/c307612/v307612158/76e4/aoXgWW8T0lY.jpg" class="img-responsive img-circle" alt="" style="height: 200px; width: 200px">
                    <h4>Andrii Vasyliev</h4>
                    <p class="text-muted">Team Lead</p>
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="team-member">
                    <img src="https://ru.gravatar.com/userimage/47567545/328b5756347b8b145b580fe629a4346e.jpg?size=200" class="img-responsive img-circle" alt="" style="height: 200px; width: 200px">
                    <h4>Dmitry Naumenko</h4>
                    <p class="text-muted">Lead Backend Developer</p>
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="team-member">
                    <img src="https://pp.vk.me/c617927/v617927068/1b3b6/3SVfzrM5ox4.jpg" class="img-responsive img-circle" alt="" style="height: 200px; width: 200px">
                    <h4>Andrey Klochok</h4>
                    <p class="text-muted">Lead Frontend Developer</p>
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="team-member">
                    <img src="http://s15.postimg.org/l9tfidcwb/x_7496e2ad.png" class="img-responsive img-circle" alt="" style="height: 200px; width: 200px">
                    <h4>Yuriy Myronchuk</h4>
                    <p class="text-muted">QA Lead</p>
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <p class="large text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
            </div>
        </div>
    </div>
</section>

<!-- Clients Aside -->
<aside class="clients">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <a href="#">
                    <img src="img/logos/envato.jpg" class="img-responsive img-centered" alt="">
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="#">
                    <img src="img/logos/designmodo.jpg" class="img-responsive img-centered" alt="">
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="#">
                    <img src="img/logos/themeforest.jpg" class="img-responsive img-centered" alt="">
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="#">
                    <img src="img/logos/creative-market.jpg" class="img-responsive img-centered" alt="">
                </a>
            </div>
        </div>
    </div>
</aside>

<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">Contact Us</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-6 contact-grid">
                            <p>Don't be shy, drop us an email and say hello! We are a really nice bunch of people :)</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="contact-left-grid">
                                        <p><i class="fa fa-mobile fa-lg"></i> (416) 555-0000</p>
                                        <p><i class="fa fa-twitter fa-lg"></i> <a href="#">@hipanel</a></p>
                                        <p><i class="fa fa-google-plus fa-lg"></i> <a href="#">plus.com/hipanel</a></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-right-grid">
                                        <p><i class="fa fa-envelope fa-lg"></i> <a href="mailto:hello@dreams.com">hello@hipanel.com</a></p>
                                        <p><i class="fa fa-facebook fa-lg"></i> <a href="#">facebook.com/hipanel</a></p>
                                        <p><i class="fa fa-vk fa-lg"></i> <a href="#">vk.com/hipanel</a></p>
                                    </div>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form name="sentMessage" id="contactForm" novalidate>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Your Name *" id="name" required data-validation-required-message="Please enter your name.">
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Your Email *" id="email" required data-validation-required-message="Please enter your email address.">
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Your Message *" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                            <button type="submit" class="btn btn-xl">Send Message</button>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>