
<div class="col-md-12">
    <h3 class="title text-black" style="font-weight: bold;color:#0E790E;font-size: large"><?php echo $page_title ?></h3>
</div>

<section>
    <div class="container mt-30 mb-30 pt-30 pb-30">
        <div class="row ">
            <div class="col-sm-12 col-md-3">
                <div class="sidebar sidebar-left mt-sm-30">
                    <div class="widget">
                        <h5 class="widget-title line-bottom">Archives</h5>
                        <ul class="list-divider list-border list check">
                            <li><a href="#">Vehicle Accidents</a></li>
                            <li><a href="#">Family Law</a></li>
                            <li><a href="#">Personal Injury</a></li>
                            <li><a href="#">Personal Injury</a></li>
                            <li><a href="#">Case Investigation</a></li>
                            <li><a href="#">Business Taxation</a></li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="blog-posts">
                    <div class="col-md-12">
                        <div class="row list-dashed">

                            <?php if (!empty($blogs)) {
                                foreach($blogs as $blog){ ?>


                            <article class="post clearfix mb-30 bg-lighter">
                                <div class="entry-header">
                                    <div class="post-thumb thumb">
                                        <img src="<?php echo $blog->image ?>" alt="" class="img-responsive img-fullwidth">
                                    </div>
                                </div>
                                <div class="entry-content p-20 pr-10">
                                    <div class="entry-meta media mt-0 no-bg no-border">
                                        <div class="entry-date media-left text-center flip bg-theme-colored pt-5 pr-15 pb-5 pl-15">
                                            <ul>
                                                <li class="font-16 text-white font-weight-600"><?php echo $blog->date ?></li>
                                            </ul>
                                        </div>
                                        <div class="media-body pl-15">
                                            <div class="event-content pull-left flip">
                                                <h4 class="entry-title text-white text-uppercase m-0 mt-5"><a href="article/<?php echo $blog->slug ?>"><?php echo $blog->title ?></a></h4>
                                                <span class="mb-10 text-gray-darkgray mr-10 font-13"><i class="fa fa-heart-o mr-5 text-theme-colored"></i> by tradermoni</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-10"> <?php echo $blog->content ?></p>
                                    <a href="/article/<?php echo $blog->slug ?>" class="btn-read-more">Read more</a>
                                    <div class="clearfix"></div>
                                </div>
                            </article>

                            <?php }}?>




                        </div>
                    </div>
                    <div class="col-md-12">
                        <nav>
                            <ul class="pagination theme-colored">
                                <li> <a aria-label="Previous" href="#"> <span aria-hidden="true">«</span> </a> </li>
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#">...</a></li>
                                <li> <a aria-label="Next" href="#"> <span aria-hidden="true">»</span> </a> </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3">
                <div class="sidebar sidebar-right mt-sm-30">

                    <div class="widget">
                        <h5 class="widget-title line-bottom">Categories</h5>
                        <div class="categories">
                            <ul class="list list-border angle-double-right">
                                <li><a href="#">Creative<span>(19)</span></a></li>
                                <li><a href="#">Portfolio<span>(21)</span></a></li>
                                <li><a href="#">Fitness<span>(15)</span></a></li>
                                <li><a href="#">Gym<span>(35)</span></a></li>
                                <li><a href="#">Personal<span>(16)</span></a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>