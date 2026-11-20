<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section id="middle">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 wow fadeInDown">
                <div class="skill">
                    <h2><?php echo_line('home_work_title'); ?></h2>
                    <p>
                        <?php echo_line('home_work_intro'); ?>
                        <br>
                    </p>
                    <h4><?php echo_line('home_work1_title'); ?></h4>
                    <p>
                        <?php echo_line('home_work1_desc'); ?>
                        <br>
                    </p>
                    <h4><?php echo_line('home_work2_title'); ?></h4>
                    <p>
                        <?php echo_line('home_work2_desc'); ?>
                    </p>
                </div>
            </div><!--/.col-sm-6-->

            <div class="col-sm-6 wow fadeInDown">
                <div class="accordion">
                    <h2><?php echo_line('home_scope_title'); ?></h2>
                    <div class="panel-group" id="accordion1">
                        <div class="panel panel-default">
                            <div class="panel-heading active">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne1">
                                        <?php echo_line('home_scope1_title'); ?>
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                </h3>
                            </div>

                            <div id="collapseOne1" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <?php echo_line('home_scope1_desc'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo1">
                                        <?php echo_line('home_scope2_title'); ?>
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseTwo1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php echo_line('home_scope2_desc'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseThree1">
                                        <?php echo_line('home_scope3_title'); ?>
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                </h3>
                            </div>
                            <div id="collapseThree1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php echo_line('home_scope3_desc'); ?>
                                </div>
                            </div>
                        </div>

                    </div><!--/#accordion1-->
                </div>
            </div>

        </div><!--/.row-->
    </div><!--/.container-->
</section><!--/#middle-->
