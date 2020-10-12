<div class="col-md-8">
<!-- Main Chart -->
    <div class="tile">
        <h2 class="tile-title">Statistics</h2>

        <div class="tile-config dropdown">
            <a data-toggle="dropdown" href="" class="tile-menu"></a>
            <ul class="dropdown-menu pull-right text-right">
                <li><a class="tile-info-toggle" href="">Chart Information</a></li>
                <li><a href="">Refresh</a></li>
                <li><a href="">Settings</a></li>
            </ul>
        </div>
        <div class="p-10">
            <div id="line-chart" class="main-chart" style="height: 250px"></div>

            <div class="chart-info">
                <ul class="list-unstyled">
                    <li class="m-b-10">
                        Total Sales 1200
                                                    <span class="pull-right text-muted t-s-0">
                                                        <i class="fa fa-chevron-up"></i>
                                                        +12%
                                                    </span>
                    </li>
                    <li>
                        <small>
                            Local 640
                            <span class="pull-right text-muted t-s-0"><i class="fa m-l-15 fa-chevron-down"></i> -8%</span>
                        </small>
                        <div class="progress progress-small">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                        </div>
                    </li>
                    <li>
                        <small>
                            Foreign 560
                                                        <span class="pull-right text-muted t-s-0">
                                                            <i class="fa m-l-15 fa-chevron-up"></i>
                                                            -3%
                                                        </span>
                        </small>
                        <div class="progress progress-small">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Pies -->
    <div class="tile text-center">
        <div class="tile-dark p-10">
            <div class="pie-chart-tiny" data-percent="86">
                <span class="percent"></span>
                <span class="pie-title">New Visitors <i class="m-l-5 fa fa-retweet"></i></span>
            </div>
            <div class="pie-chart-tiny" data-percent="23">
                <span class="percent"></span>
                <span class="pie-title">Bounce Rate <i class="m-l-5 fa fa-retweet"></i></span>
            </div>
            <div class="pie-chart-tiny" data-percent="57">
                <span class="percent"></span>
                <span class="pie-title">Emails Sent <i class="m-l-5 fa fa-retweet"></i></span>
            </div>
            <div class="pie-chart-tiny" data-percent="34">
                <span class="percent"></span>
                <span class="pie-title">Sales Rate <i class="m-l-5 fa fa-retweet"></i></span>
            </div>
            <div class="pie-chart-tiny" data-percent="81">
                <span class="percent"></span>
                <span class="pie-title">New Signups <i class="m-l-5 fa fa-retweet"></i></span>
            </div>
        </div>
    </div>

    <!--  Recent Postings -->
    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <h2 class="tile-title">Recent Postings</h2>

                <div class="tile-config dropdown">
                    <a data-toggle="dropdown" href="" class="tile-menu"></a>
                    <ul class="dropdown-menu animated pull-right text-right">
                        <li><a href="">Refresh</a></li>
                        <li><a href="">Settings</a></li>
                    </ul>
                </div>

                <div class="listview narrow">
                    <div class="media p-l-5">
                        <div class="pull-left">
                            <img width="40" src="<?= asset('bootstrap/img/profile-pics/1.jpg') ?>" alt="">
                        </div>
                        <div class="media-body">
                            <small class="text-muted">2 Hours ago by Adrien San</small>
                            <br/>
                            <a class="t-overflow" href="">Cras molestie fermentum nibh, ac semper</a>

                        </div>
                    </div>
                    <div class="media p-l-5">
                        <div class="pull-left">
                            <img width="40" src="<?= asset('bootstrap/img/profile-pics/2.jpg') ?>" alt="">
                        </div>
                        <div class="media-body">
                            <small class="text-muted">5 Hours ago by David Villa</small>
                            <br/>
                            <a class="t-overflow" href="">Suspendisse in purus ut nibh placerat</a>

                        </div>
                    </div>
                    <div class="media p-l-5">
                        <div class="pull-left">
                            <img width="40" src="<?= asset('bootstrap/img/profile-pics/3.jpg') ?>" alt="">
                        </div>
                        <div class="media-body">
                            <small class="text-muted">On 15/12/2013 by Mitch bradberry</small>
                            <br/>
                            <a class="t-overflow" href="">Cras pulvinar euismod nunc quis gravida. Suspendisse pharetra</a>

                        </div>
                    </div>
                    <div class="media p-l-5">
                        <div class="pull-left">
                            <img width="40" src="<?= asset('bootstrap/img/profile-pics/4.jpg') ?>" alt="">
                        </div>
                        <div class="media-body">
                            <small class="text-muted">On 14/12/2013 by Mitch bradberry</small>
                            <br/>
                            <a class="t-overflow" href="">Cras pulvinar euismod nunc quis gravida. </a>

                        </div>
                    </div>
                    <div class="media p-l-5">
                        <div class="pull-left">
                            <img width="40" src="<?= asset('bootstrap/img/profile-pics/5.jpg') ?>" alt="">
                        </div>
                        <div class="media-body">
                            <small class="text-muted">On 13/12/2013 by Mitch bradberry</small>
                            <br/>
                            <a class="t-overflow" href="">Integer a eros dapibus, vehicula quam accumsan, tincidunt purus</a>

                        </div>
                    </div>
                    <div class="media p-5 text-center l-100">
                        <a href="">
                            <small>VIEW ALL</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks to do -->
        <div class="col-md-6">
            <div class="tile">
                <h2 class="tile-title">Tasks to do</h2>

                <div class="tile-config dropdown">
                    <a data-toggle="dropdown" href="" class="tile-menu"></a>
                    <ul class="dropdown-menu pull-right text-right">
                        <li id="todo-add"><a href="">Add New</a></li>
                        <li id="todo-refresh"><a href="">Refresh</a></li>
                        <li id="todo-clear"><a href="">Clear All</a></li>
                    </ul>
                </div>

                <div class="listview todo-list sortable">
                    <div class="media">
                        <div class="checkbox m-0">
                            <label class="t-overflow">
                                <input type="checkbox">
                                Curabitur quis nisi ut nunc gravida suscipis
                            </label>
                        </div>
                    </div>
                    <div class="media">
                        <div class="checkbox m-0">
                            <label class="t-overflow">
                                <input type="checkbox">
                                Suscipit at feugiat dewoo
                            </label>
                        </div>

                    </div>
                    <div class="media">
                        <div class="checkbox m-0">
                            <label class="t-overflow">
                                <input type="checkbox">
                                Gravida wendy lorem ipsum seen
                            </label>
                        </div>

                    </div>
                    <div class="media">
                        <div class="checkbox m-0">
                            <label class="t-overflow">
                                <input type="checkbox">
                                Fedrix quis nisi ut nunc gravida suscipit at feugiat purus
                            </label>
                        </div>

                    </div>
                </div>

                <h2 class="tile-title">Completed Tasks</h2>

                <div class="listview todo-list sortable">
                    <div class="media">
                        <div class="checkbox m-0">
                            <label class="t-overflow">
                                <input type="checkbox" checked="checked">
                                Motor susbect win latictals bin the woodat cool
                            </label>
                        </div>

                    </div>
                    <div class="media">
                        <div class="checkbox m-0">
                            <label class="t-overflow">
                                <input type="checkbox" checked="checked">
                                Wendy mitchel susbect win latictals bin the woodat cool
                            </label>
                        </div>

                    </div>
                    <div class="media">
                        <div class="checkbox m-0">
                            <label class="t-overflow">
                                <input type="checkbox" checked="checked">
                                Latictals bin the woodat cool for the win
                            </label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="col-md-4">
    <!-- USA Map -->
    <div class="tile">
        <h2 class="tile-title">Live Visits</h2>

        <div class="tile-config dropdown">
            <a data-toggle="dropdown" href="" class="tile-menu"></a>
            <ul class="dropdown-menu pull-right text-right">
                <li><a href="">Refresh</a></li>
                <li><a href="">Settings</a></li>
            </ul>
        </div>

        <div id="usa-map"></div>
    </div>

    <!-- Dynamic Chart -->
    <div class="tile">
        <h2 class="tile-title">Server Process</h2>

        <div class="tile-config dropdown">
            <a data-toggle="dropdown" href="" class="tile-menu"></a>
            <ul class="dropdown-menu pull-right text-right">
                <li><a href="">Refresh</a></li>
                <li><a href="">Settings</a></li>
            </ul>
        </div>

        <div class="p-t-10 p-r-5 p-b-5">
            <div id="dynamic-chart" style="height: 200px"></div>
        </div>

    </div>

    <!-- Activity -->
    <div class="tile">
        <h2 class="tile-title">Social Media activities</h2>

        <div class="tile-config dropdown">
            <a data-toggle="dropdown" href="" class="tile-menu"></a>
            <ul class="dropdown-menu pull-right text-right">
                <li><a href="">Refresh</a></li>
                <li><a href="">Settings</a></li>
            </ul>
        </div>

        <div class="listview narrow">

            <div class="media">
                <div class="pull-right">
                    <div class="counts">367892</div>
                </div>
                <div class="media-body">
                    <h6>FACEBOOK LIKES</h6>
                </div>
            </div>

            <div class="media">
                <div class="pull-right">
                    <div class="counts">2012</div>
                </div>
                <div class="media-body">
                    <h6>GOOGLE +1s</h6>
                </div>
            </div>

            <div class="media">
                <div class="pull-right">
                    <div class="counts">56312</div>
                </div>
                <div class="media-body">
                    <h6>YOUTUBE VIEWS</h6>
                </div>
            </div>

            <div class="media">
                <div class="pull-right">
                    <div class="counts">785879</div>
                </div>
                <div class="media-body">
                    <h6>TWITTER FOLLOWERS</h6>
                </div>
            </div>
            <div class="media">
                <div class="pull-right">
                    <div class="counts">68</div>
                </div>
                <div class="media-body">
                    <h6>WEBSITE COMMENTS</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
