<!DOCTYPE html>
<html lang="th" xmlns:fb="http://www.facebook.com/2008/fbml"> 
    <head>
        <?php $this->load->view('design/component/common/meta_1'); ?>
        <title><?php echo htmlspecialchars(strip_tags($page_title), ENT_QUOTES, 'UTF-8'); ?> | ไทย-สันสคริปท์</title>
        <?php $this->load->view('design/component/common/import_css'); ?>
        <?php $this->load->view('design/component/common/analyticstracking'); ?>
		
	
    </head>
    <body class="homepage">
        <?php $this->load->view('design/component/common/google_tag_manager'); ?>
        <?php $this->load->view('design/component/common/facebook_sdk'); ?>
        <!-- begin content --> 
        <?php foreach ($page_section as $section): ?>
            <?php $this->load->view($section); ?>
        <?php endforeach; ?>
        <!-- end content -->  
        <div class="row contact-wrap"></div>
        <?php $this->load->view('design/component/common/import_js'); ?> 
        <?php $this->load->view('main/transliterate/form-compare-js'); ?> 

       
    </body>
</html>
