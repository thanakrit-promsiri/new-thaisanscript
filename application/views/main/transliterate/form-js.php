<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    function updateTranslatorPaneState() {
        var srcVal = getSrcTypeval();
        var destVal = getDestTypeVal();

        $("#pane-src-label").text(getSrcTypeText());
        $("#pane-dest-label").text(getDestTypeText());

        if (srcVal === 'thai') {
            $("#hint-thai").show();
        } else {
            $("#hint-thai").hide();
        }

        if (srcVal === 'lao' || destVal === 'lao') {
            $("#hint-lao").show();
        } else {
            $("#hint-lao").hide();
        }

        $("#src-txt").toggleClass("lao", srcVal === 'lao');
        $("#dest-txt").toggleClass("lao", destVal === 'lao');
        $("#src-txt").toggleClass("brahmi", srcVal === 'brahmi');
        $("#dest-txt").toggleClass("brahmi", destVal === 'brahmi');

        // Live preview for all scripts (including Burma via Sanscript.t)
        $("#dest-txt").val(transliteration());
    }

    $("#src-type, #dest-type, #lang").on('change', function () {
        updateTranslatorPaneState();
    });

    $("#src-txt").on('input keyup change paste', function () {
        $("#dest-txt").val(transliteration());
    });

    $("#swap-script-btn").on('click', function () {
        var srcVal = getSrcTypeval();
        var destVal = getDestTypeVal();
        // Swap only if both scripts exist in the opposite dropdown
        if ($("#src-type option[value='" + destVal + "']").length &&
            $("#dest-type option[value='" + srcVal + "']").length) {
            var currentOut = $("#dest-txt").val();
            $("#src-type").val(destVal);
            $("#dest-type").val(srcVal);
            if (currentOut && currentOut.trim() !== "") {
                $("#src-txt").val(currentOut);
            }
            $("#src-type").trigger('change');
        }
    });

    $("#clear-src-btn").on('click', function () {
        $("#src-txt").val('').focus();
        $("#dest-txt").val('');
    });

    $("#copy-dest-btn").on('click', function () {
        var text = $("#dest-txt").val();
        if (!text) return;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text);
        } else {
            var el = document.getElementById('dest-txt');
            el.select();
            document.execCommand('copy');
        }
        $("#copy-dest-text").text("<?php echo_line('copied_text'); ?>");
        setTimeout(function () {
            $("#copy-dest-text").text("<?php echo_line('copy_text'); ?>");
        }, 1800);
    });

    $(document).ready(function () {
        updateTranslatorPaneState();
    });
</script>
