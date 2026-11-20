<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap');

    @font-face {
        font-family: Laobuhan;
        src: url(<?php echo_css("Laobuhan.otf") ?>);
    }
    /* fallback for Lao letters that Laobuhan does not contain */
    @font-face {
        font-family: Lanexang_Pali2;
        src: url(<?php echo_css("Lanexang_Pali2.otf") ?>);
    }

    .lao {
        font-family: Laobuhan, Lanexang_Pali2;
    }

    .glyphicon-class-dest {
        display: block;
        text-align: center;
        word-wrap: break-word;
        font-size: 20px;
        font-weight: bold;
        -webkit-font-smoothing: antialiased;
        color: #1d4ed8;
    }
    .center {
        text-align: center;
        padding-bottom: 5px;
    }
</style>

<section id="contact-info" class="modern-section">
    <div id="translite-form" class="container">
        <div class="modern-hero">
            <h2><?php echo echo_line("Select the character type to convert"); ?></h2>
            <p class="lead">
                <?php echo echo_line("Select the original character."); ?>
                <?php echo echo_line("Enter the character of the selected character type."); ?>
                <?php echo echo_line("Then select other comparative characters and"); ?>
                <?php echo echo_line("Then select output language"); ?>
                <?php echo echo_line("press the 'Convert' button"); ?>
                <?php echo echo_line("to view the results."); ?>
            </p>
            <div class="hero-badges">
                <a href="#contact-page" class="hero-badge">
                    <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                    <?php echo echo_line("View the comparative table"); ?>
                </a>
                <a href="<?php echo_css("Laobuhan.otf") ?>" class="hero-badge">
                    <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
                    <?php echo echo_line("for Lao script please install font"); ?> : <strong>Laobuhan</strong>
                </a>
                <a id="hint-thai" href="#" data-toggle="modal" data-target="#agreement" class="hero-badge">
                    <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
                    <?php echo_line("Agreement of using"); ?>
                </a>
                <a id="hint-lao" href="#" data-toggle="modal" data-target="#agreement-lao" class="hero-badge" style="display: none">
                    <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
                    <?php echo_line("install_lao_font"); ?>
                </a>
            </div>
        </div>

        <form id="main-contact-form" class="contact-form" name="contact-form" method="post">
            <div class="translator-card">
                <!-- Top Language Bar -->
                <div class="translator-toolbar">
                    <div class="toolbar-col toolbar-source">
                        <label for="src-type"><?php echo echo_line("Original Character"); ?></label>
                        <select name="src-type" id="src-type" class="form-control select-type translite">
                            <optgroup label="<?php echo_line("Southeast Asia"); ?>">
                                <option value="thai"><?php echo_line("Thai Traditional Form"); ?></option>
                                <option value="burma"><?php echo_line("Burmese"); ?></option>
                                <option value="lao"><?php echo_line("Lao"); ?> (ກ)</option>
                            </optgroup>
                            <optgroup label="<?php echo_line("Indian Scripts"); ?>">
                                <option value="devanagari" selected="selected"><?php echo_line("Devanagari"); ?> (अ)</option>
                                <option value="bengali"><?php echo_line("Bengali"); ?> (অ)</option>
                                <option value="gurmukhi"><?php echo_line("Gurmukhi"); ?> (ਅ)</option>
                                <option value="gujarati"><?php echo_line("Gujarati"); ?> (અ)</option>
                                <option value="oriya"><?php echo_line("Oriya"); ?> (ଅ)</option>
                                <option value="tamil"><?php echo_line("Tamil"); ?> (அ)</option>
                                <option value="telugu"><?php echo_line("Telugu"); ?> (అ)</option>
                                <option value="kannada"><?php echo_line("Kannada"); ?> (ಅ)</option>
                                <option value="malayalam"><?php echo_line("Malayalam"); ?> (അ)</option>
                            </optgroup>
                            <optgroup label="<?php echo_line("Romanize"); ?>">
                                <option value="iast">IAST</option>
                                <option value="kolkata">Kolkata</option>
                                <option value="itrans">ITRANS</option>
                                <option value="hk">Harvard-Kyoto</option>
                                <option value="slp1">SLP</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="toolbar-swap">
                        <button type="button" id="swap-script-btn" class="btn-swap" title="สลับอักษรต้นทาง-ปลายทาง">
                            <span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>
                        </button>
                    </div>

                    <div class="toolbar-col toolbar-dest">
                        <label for="dest-type"><?php echo echo_line("Other Characters"); ?></label>
                        <select class="form-control select-type translite" name="dest-type" id="dest-type">
                            <optgroup label="<?php echo_line("Southeast Asia"); ?>">
                                <option value="lao"><?php echo echo_line("Lao"); ?> (ກ)</option>
                            </optgroup>
                            <optgroup label="<?php echo_line("Indian Scripts"); ?>">
                                <option value="devanagari"><?php echo_line("Devanagari"); ?> (अ)</option>
                                <option value="bengali"><?php echo_line("Bengali"); ?> (অ)</option>
                                <option value="gurmukhi"><?php echo_line("Gurmukhi"); ?> (ਅ)</option>
                                <option value="gujarati"><?php echo_line("Gujarati"); ?> (અ)</option>
                                <option value="oriya"><?php echo_line("Oriya"); ?> (ଅ)</option>
                                <option value="tamil"><?php echo_line("Tamil"); ?> (அ)</option>
                                <option value="telugu"><?php echo_line("Telugu"); ?> (అ)</option>
                                <option value="kannada"><?php echo_line("Kannada"); ?> (ಅ)</option>
                                <option value="malayalam"><?php echo_line("Malayalam"); ?> (അ)</option>
                            </optgroup>
                            <optgroup label="<?php echo_line("Romanize"); ?>">
                                <option value="iast" selected="selected">IAST</option>
                                <option value="kolkata">Kolkata</option>
                                <option value="itrans">ITRANS</option>
                                <option value="hk">Harvard-Kyoto</option>
                                <option value="slp1">SLP</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="toolbar-col toolbar-lang">
                        <label for="lang"><?php echo echo_line("Output Language"); ?></label>
                        <select class="form-control select-type translite" name="lang" id="lang">
                            <option value="sans" selected="selected"><?php echo_line("Sanskrit"); ?></option>
                            <option value="pali"><?php echo_line("Pali"); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Side-by-Side Input & Instant Output Panels -->
                <div class="translator-panes">
                    <div class="translator-pane pane-source">
                        <div class="pane-header">
                            <span id="pane-src-label" class="pane-title">เทวนาครี (अ)</span>
                            <button type="button" id="clear-src-btn" class="btn-pane-action" title="<?php echo_line("clear_text"); ?>">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> <?php echo_line("clear_text"); ?>
                            </button>
                        </div>
                        <textarea name="src-txt" id="src-txt" required="required" class="form-control translite" rows="7" placeholder="<?php echo_line("input_placeholder"); ?>">गोब्राह्मणेभ्यः शुभमस्तु नित्यं लोकाः समस्ताः सुखिनोभवन्तु ॥</textarea>
                    </div>

                    <div class="translator-pane pane-dest">
                        <div class="pane-header">
                            <span id="pane-dest-label" class="pane-title">IAST</span>
                            <button type="button" id="copy-dest-btn" class="btn-pane-action" title="<?php echo_line("copy_text"); ?>">
                                <span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span> <span id="copy-dest-text"><?php echo_line("copy_text"); ?></span>
                            </button>
                        </div>
                        <textarea name="dest-txt" id="dest-txt" readonly="readonly" class="form-control" rows="7" placeholder="<?php echo_line("output_placeholder"); ?>">gobrāhmaṇebhyaḥ śubhamastu nityaṃ lokāḥ samastāḥ sukhinobhavantu ॥</textarea>
                    </div>
                </div>

                <!-- Bottom Action Bar -->
                <div class="translator-footer">
                    <div class="footer-note">
                        <?php echo_line("public_domain_note"); ?>
                        <span class="text-muted">• Burmese Script by Aksharamukha</span>
                    </div>
                    <a href="#transliterate-compare" id="translite-button" class="btn-convert-modern page-scroll">
                        <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span>
                        &nbsp;<?php echo_line("Convert"); ?> &amp; <?php echo_line("compare_word_by_word"); ?>
                    </a>
                </div>
            </div>
        </form>
    </div><!--/.container-->

    <div id="transliterate-compare" class="container">
        <!--Call Ajax-->
    </div>
</section>
