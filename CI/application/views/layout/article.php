<section>
    <div class="container mt-30 mb-30 pt-30 pb-30">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="blog-posts single-post">
                    <article class="post clearfix mb-0">
                        <div class="entry-header">
                            <div class="post-thumb thumb"> <img src="<?php echo $blog->image?>" alt="blog image" class="img-responsive img-fullwidth"> </div>
                        </div>
                        <div class="entry-content">
                            <div class="entry-meta media no-bg no-border mt-15 pb-20">
                                <div class="entry-date media-left text-center flip bg-theme-colored pt-5 pr-15 pb-5 pl-15">
                                    <ul>
                                        <li class="font-16 text-white font-weight-600"><?php echo $blog->date?></li>
                                    </ul>
                                </div>
                                <div class="media-body pl-15">
                                    <div class="event-content pull-left flip">
                                        <h4 class="entry-title text-white text-uppercase m-0"><a href="<?php echo $blog->slug?>"><?php echo $blog->title?></a></h4>
                                        <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-commenting-o mr-5 text-theme-colored"></i> 214 Comments</span>
                                        <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-heart-o mr-5 text-theme-colored"></i> 895 Likes</span>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-15"> <?php echo $blog->content?></p>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna et sed aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            <div class="mt-30 mb-0">
                                <h5 class="pull-left mt-10 mr-20 text-theme-colored">Share:</h5>
                                <ul class="social-icons icon-circled m-0">
                                    <li><a href="#" data-bg-color="#3A5795" style="background: rgb(58, 87, 149) none repeat scroll 0% 0% !important;"><i class="fa fa-facebook text-white"></i></a></li>
                                    <li><a href="#" data-bg-color="#55ACEE" style="background: rgb(85, 172, 238) none repeat scroll 0% 0% !important;"><i class="fa fa-twitter text-white"></i></a></li>
                                    <li><a href="#" data-bg-color="#A11312" style="background: rgb(161, 19, 18) none repeat scroll 0% 0% !important;"><i class="fa fa-google-plus text-white"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </article>

                </div>
            </div>
        </div>
    </div>
</section>