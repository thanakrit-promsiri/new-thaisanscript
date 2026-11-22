<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
    $(document).on({
        ajaxStart: function () {
            $("body").addClass("loading");
        },
        ajaxStop: function () {
            $("body").removeClass("loading");
        }
    });

    $('#translite-button').click(function () {
        var sourceTxt = getSrcTxtval();
        var source = lineSplit(sourceTxt);
        var srcType = getSrcTypeval();

        function runBackendCompare(sendToBackend, destinationStr) {
            var destination = lineSplit(destinationStr);

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('Transliteration/json'); ?>",
                method: "POST",
                dataType: 'json',
                data: {
                    "src-txt": sendToBackend,
                    "lang": getLang(),
                    "srcType": srcType,
                    "destType": getDestTypeVal()
                },
                success: function (data) {
                    var display = new Array(1, 1, 1, 1);
                    var showAndCheck = new Array(1, 0, 1, 0);

                    if (getSrcTypeval() === 'thai') {
                        display = new Array(1, 1, 0, 1);
                        showAndCheck = new Array(1, 1, 0, 0);
                    }
                    var normal_header = '<?php echo echo_line("Thai Simple(General)"); ?>';
                    var normal_type = "thai";
                    if (getDestTypeVal() === 'lao') {
                        destination = data[2];
                        normal_header = '<?php echo_line("lao_adjusted"); ?>';
                        normal_type = "lao";
                    }

                    var html = '<div class="compare-result-card">' +
                            header() +
                            checkboxList(display, showAndCheck) +
                            '<div class="compare-columns-grid">' +
                            langSection('1', getSrcTypeText(), source, display[0], showAndCheck[0], getSrcTypeval()) +
                            langSection('2', getDestTypeText(), destination, display[1], showAndCheck[1], getDestTypeVal()) +
                            langSection('3', '<?php echo echo_line("Thai Traditional(Formal)"); ?>', data[0], display[2], showAndCheck[2], "thai") +
                            langSection('4', normal_header, data[1], display[3], showAndCheck[3], normal_type) +
                            '</div>' +
                            backButton() +
                            '</div>';
                    $('#transliterate-compare').html(html);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    XMLHttpRequest = "";
                    console.log("Status: " + textStatus);
                    console.log("Error: " + errorThrown);
                }
            });
        }

        runBackendCompare(transliteToBackend(), transliteration());
    });

    $(document).on('click', '.checkbox-sanskrit', function () {
        var val = $(this).val();
        var $chip = $(this).closest('.filter-chip');
        if ($(this).is(':checked')) {
            $("#" + val).show();
            $chip.addClass('active');
        } else {
            $("#" + val).hide();
            $chip.removeClass('active');
        }
    });

    $(document).on('click', '.s', function () {
        var str = $(this).attr('id');
        var idsplit = str.split("-");
        var num = idsplit[1] + "-" + idsplit[2];

        $('.s').removeClass('hilight');
        $("#0-" + num).addClass('hilight');
        $("#1-" + num).addClass('hilight');
        $("#2-" + num).addClass('hilight');
        $("#3-" + num).addClass('hilight');
        $("#4-" + num).addClass('hilight');
    });

    $(document).on('click', '.btn-copy-col', function () {
        var colId = $(this).attr('data-col');
        var lines = [];
        $("#" + colId + " .code-li").each(function () {
            lines.push($(this).text().trim());
        });
        var text = lines.join("\n");
        if (!text) return;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text);
        }
        var $btn = $(this);
        var oldHtml = $btn.html();
        $btn.text("<?php echo_line('copied_text'); ?>");
        setTimeout(function () {
            $btn.html(oldHtml);
        }, 1500);
    });

    function getSyllable(langCode, lineNum, line) {
        var html = "";
        for (var i in line) {
            var id = langCode + "-" + lineNum + "-" + i;
            html += '<span id="' + id + '" class="s">' + line[i] + ' </span>';
        }
        return html;
    }

    function getLine(langCode, lineArr) {
        var html = "";
        for (var lineNum in lineArr) {
            var syllable = getSyllable(langCode, lineNum, lineArr[lineNum]);
            html += '<li class="code-li"><div>' + syllable + '</div></li>';
        }
        return html;
    }

    function langSection(langCode, langName, dataList, display, showAndCheck, langCodetype) {
        var html = '';
        var show = '';
        if (showAndCheck === 0) {
            show = ' style="display:none" ';
        }
        if (display === 1) {
            html = '<div id="' + langCode + '" class="code ' + langCodetype + '" ' + show + '>' +
                    '<div class="code-header">' +
                    '<p class="code-p">' + langName + '</p>' +
                    '<button type="button" class="btn-copy-col" data-col="' + langCode + '" title="<?php echo_line("copy_text"); ?>">' +
                    '<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span> <?php echo_line("copy_text"); ?>' +
                    '</button>' +
                    '</div>' +
                    '<ol class="code-ol ' + langCodetype + '">' + getLine(langCode, dataList) + '</ol></div>';
        }
        return html;
    }

    function header() {
        var html = '<div class="compare-header text-center">' +
                '<h3 class="section-heading"><?php echo echo_line("Select the character type to convert"); ?></h3>' +
                '<p class="compare-subtitle"><?php echo echo_line("Click on the word."); ?> <?php echo echo_line("It will mark the highlight color to compare"); ?></p>' +
                '<h5 id="time-process" class="section-heading"></h5>' +
                '</div>';
        return html;
    }

    function checkboxList(displayArr, showAndCheckArr) {
        var normal_header = '<?php echo echo_line("Thai Simple(General)"); ?>';
        if (getDestTypeVal() === 'lao') {
            normal_header = '<?php echo_line("lao_adjusted"); ?>';
        }

        var html = '<div class="filter-chips-bar">' +
                checkbox(displayArr[0], showAndCheckArr[0], 1, getSrcTypeText()) +
                checkbox(displayArr[1], showAndCheckArr[1], 2, getDestTypeText()) +
                checkbox(displayArr[2], showAndCheckArr[2], 3, '<?php echo echo_line("Thai Traditional(Formal)"); ?>') +
                checkbox(displayArr[3], showAndCheckArr[3], 4, normal_header) +
                '</div>';
        return html;
    }

    function checkbox(display, showAndCheck, number, name) {
        var html = '';
        var check = '';
        var activeClass = '';
        if (showAndCheck === 1) {
            check = 'checked="checked"';
            activeClass = 'active';
        }
        if (display === 1) {
            html = '<label class="filter-chip ' + activeClass + '" for="checkbox-' + number + '">' +
                    '<input type="checkbox" class="checkbox-sanskrit" id="checkbox-' + number + '" value="' + number + '" ' + check + '>' +
                    '<span>' + name + '</span>' +
                    '</label>';
        }
        return html;
    }

    function backButton() {
        var html = '<div class="text-center" style="margin-top: 24px;">' +
                '<a href="#translite-form" class="btn-back-top page-scroll">' +
                '<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> ' +
                '<?php echo_line("back_to_top"); ?></a></div>';
        return html;
    }

    function syllableSplit(line) {
        line = line.trim().replace(/\x20+/g, '@');
        return line.trim().split('@');
    }

    function lineSplit(txt) {
        var lineSplit = txt.trim().split(/\r\n|\r|\n/);
        var line = new Array();
        for (var i in lineSplit) {
            line.push(syllableSplit(lineSplit[i]));
        }
        return line;
    }
</script>
