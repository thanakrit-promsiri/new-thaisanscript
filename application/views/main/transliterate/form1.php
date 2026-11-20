<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    @font-face {
        font-family: Laobuhan;
        src: url(<?php echo_css("Laobuhan.otf") ?>);
    }
    /* fallback for Lao letters that Laobuhan does not contain */
    @font-face {
        font-family: Lanexang_Pali2;
        src: url(<?php echo_css("Lanexang_Pali2.otf") ?>);
    }

    .lao{
        font-family: Laobuhan, Lanexang_Pali2;
    }

    .glyphicon-class-dest {
        display: block;
        text-align: center;
        word-wrap: break-word;
        font-size: 19px;
        font-weight: bold;

        -webkit-font-smoothing: antialiased;
        color: #003399;
    }
    .center {
        text-align: center;
        padding-bottom: 5px; 
    }

</style>
<section id="contact-info" style="background: #f2f2f2;">
    <div id="translite-form" class="container">
        <div class="center">        
            <h2><?php echo echo_line("Select the character type to convert"); ?></h2>
            <p class="lead">
                <?php echo echo_line("When you have selected a character type, you can view the comparative table below."); ?> 
                [<a href="#contact-page"><?php echo echo_line("View the comparative table"); ?> </a>]<br>
                <?php echo echo_line("Select the original character."); ?> 
                <?php echo echo_line("Enter the character of the selected character type."); ?> <br>
                <?php echo echo_line("Then select other comparative characters and"); ?> 
                <?php echo echo_line("Then select output language"); ?><br>
                <?php echo echo_line("press the 'Convert' button"); ?> 
                <?php echo echo_line("to view the results."); ?><br>
                (<?php echo echo_line("for Lao script please install font"); ?> : <a href="<?php echo_css("Laobuhan.otf") ?>" >Laobuhan</a>)<br>
				ไม่สงวนลิขสิทธิ์ ผลการปริวรรตถือเป็นผลงานของทุกคน ไม่ต้องอ้างอิง และนำไปใช้เพื่อการค้าได้ <br><br>
				<b>เพื่อเป็นการสนับสนุนเว็บไซต์ให้เปิดใช้บริการต่อไปได้ <br> ขอความอนุเคราะห์สมาชิกเว็บไซต์ คลิกลิงก์โฆษณาจากทาง google ด้านล่าง <br>เพื่อจะนำค่าโฆษณาอันน้อยนิดที่ได้ นำสมทบค่าเช่าเซิร์ฟเวอร์เว็บไซต์ </b>
            </p>
        </div> 
        <div class="row contact-wrap"> 
            <div class="status alert alert-success" style="display: none"></div>
            <form id="main-contact-form" class="contact-form" name="contact-form" method="post" action="">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="form-group">
                        <label><?php echo echo_line("Original Character"); ?> </label>   
                        <a id="hint-thai" href="#" data-toggle="modal" data-target="#agreement"><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span><?php echo_line("Agreement of using"); ?></a>
                        <a id="hint-lao" href="#" data-toggle="modal" data-target="#agreement-lao" style="display: none"><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>ติดตั้งฟอนต์ลาว</a>                  
                        <select name="src-type"  id="src-type" class="form-control select-type translite" style="overflow: scroll;" >
                            <optgroup label="<?php echo_line("Southeast Asia"); ?>">
                                <option value="thai"><?php echo_line("Thai Traditional Form"); ?></option>
                                <option value="lao" ><?php echo_line("Lao"); ?> <span class="lao">(ກ)</span></option>
                            <option value="burmese"><?php echo_line("Burmese"); ?> (က)</option>                                
                            </optgroup>
                            <!--<optgroup label="ทดสอบ">-->
                            <!--<option value="brahmi">พราหมี (𑀅)</option>-->                           
                            <!--</optgroup>-->

                            <optgroup label="<?php echo_line("Indian Scripts"); ?>">                             
                                <option value="devanagari" selected="selected" > <?php echo_line("Devanagari"); ?> (अ)</option>
                                <option value="bengali"><?php echo_line("Bengali"); ?>  (অ)</option>
                                <option value="gurmukhi"><?php echo_line("Gurmukhi"); ?> (ਅ)</option>
                                <option value="gujarati"><?php echo_line("Gujarati"); ?> (અ)</option>
                                <option value="oriya"><?php echo_line("Oriya"); ?> (ଅ)</option>
                                <option value="tamil"><?php echo_line("Tamil"); ?> (அ)</option>
                                <option value="telugu"><?php echo_line("Telugu"); ?> (అ)</option>
                                <option value="kannada"><?php echo_line("Kannada"); ?> (ಅ)</option>
                                <option value="malayalam"><?php echo_line("Malayalam"); ?> (അ)</option>
                            </optgroup>
                            <optgroup label="<?php echo_line("Romanize"); ?>">
                                <option value="iast" > IAST</option>
                                <option value="kolkata"> Kolkata</option>
                                <option value="itrans"> ITRANS</option>
                                <option value="hk"> Harvard-Kyoto</option>
                                <option value="slp1"> SLP</option>
                                <!--<option value="iasttest">โรมัน ทดสอบ</option>-->
                            </optgroup>                           
                        </select>
                    </div>
                    <!--
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea name="src-txt"  id="src-txt" required="required" class="form-control translite" rows="8">गोब्राह्मणेभ्यः शुभमस्तु नित्यं लोकाः समस्ताः सुखिनोभवन्तु ॥</textarea>
                    </div> 
                    -->
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label><?php echo echo_line("Other Characters"); ?></label>
                        <select class="form-control select-type translite" name="dest-type"  id="dest-type">
                            <!--
                            <optgroup label="อินเดียโบราณ">
                                <option value="brahmi">พราหมี (𑀅)</option> 
                                <option value="thai"  >ไทย-คงรูป(แบบแผน)</option>    
                            </optgroup>
                            -->
                            <optgroup label="<?php echo_line("Southeast Asia"); ?>">                               
                                <option value="lao" ><?php echo echo_line("Lao"); ?> <span class="lao">(ກ)</span></option>                                       
                            </optgroup>
                            <optgroup label="<?php echo echo_line("Indian Scripts"); ?>">                             
                                <option value="devanagari"  > <?php echo_line("Devanagari"); ?> (अ)</option>
                                <option value="bengali"><?php echo_line("Bengali"); ?>  (অ)</option>
                                <option value="gurmukhi"><?php echo_line("Gurmukhi"); ?> (ਅ)</option>
                                <option value="gujarati"><?php echo_line("Gujarati"); ?> (અ)</option>
                                <option value="oriya"><?php echo_line("Oriya"); ?> (ଅ)</option>
                                <option value="tamil"><?php echo_line("Tamil"); ?> (அ)</option>
                                <option value="telugu"><?php echo_line("Telugu"); ?> (అ)</option>
                                <option value="kannada"><?php echo_line("Kannada"); ?> (ಅ)</option>
                                <option value="malayalam"><?php echo_line("Malayalam"); ?> (അ)</option>
                            </optgroup>
                            <optgroup label="<?php echo_line("Romanize"); ?>">
                                <option value="iast" selected="selected" > IAST</option>
                                <option value="kolkata"> Kolkata</option>
                                <option value="itrans"> ITRANS</option>
                                <option value="hk"> Harvard-Kyoto</option>
                                <option value="slp1"> SLP</option>
                                <!--<option value="iasttest">โรมัน ทดสอบ</option>-->
                            </optgroup>                           

                        </select>
                    </div>
                    <!--                                        
                    <div class="form-group">                                                                
                    <label>Message *</label>                                                                
                    <textarea name="dest-txt"  id="dest-txt" required="required" class="form-control translite" rows="8" maxlength="1000">gobrāhmaṇebhyaḥ śubhamastu nityaṃ lokāḥ samastāḥ sukhinobhavantu ॥</textarea>
                                                     
                    </div>                                      
                    -->
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label><?php echo echo_line("Output Language"); ?></label>
                        <select class="form-control select-type translite" name="lang"  id="lang" >
                            <option value="sans" selected="selected" ><?php echo_line("Sanskrit"); ?></option>
                            <option value="pali"><?php echo_line("Pali"); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group center" style="margin-left: 8.333333333333332%;width: 85%;">
                    <textarea name="src-txt"  id="src-txt" required="required" class="form-control translite" rows="8">गोब्राह्मणेभ्यः शुभमस्तु नित्यं लोकाः समस्ताः सुखिनोभवन्तु ॥</textarea>
                </div>
                <div class="form-group center">
                    <a href="#transliterate-compare" id="translite-button" class="btn btn-info btn-xl page-scroll">
                        <span class="glyphicon glyphicon glyphicon-transfer" aria-hidden="true"></span>
                        &nbsp;<?php echo_line("Convert"); ?>
                    </a>
                    <!--<button id="translite-button" type="button" name="submit" class="btn btn-primary btn-lg" required="required">สร้างตารางเปรียบเทียบ</button>-->
                </div>
            </form> 
        </div><!--/.row-->      
    </div><!--/.container-->
    <div id="transliterate-compare">
        <!--Call Ajax-->
    </div>

    <div class="row contact-wrap"></div>
</section><!--/#contact-page-->