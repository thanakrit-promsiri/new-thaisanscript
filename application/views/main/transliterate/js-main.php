<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">

    //console.log(Sanscript.t("𑀕𑁅𑀩𑁆𑀭𑀸𑀳𑁆𑀫𑀡𑁂𑀪𑁆𑀬𑀂 𑀰𑀻𑀪𑀫𑀲𑁆𑀢𑀻 𑀦𑀹𑀢𑁆𑀬𑀁 𑀮𑁅𑀓𑀸𑀂 𑀲𑀫𑀲𑁆𑀢𑀸𑀂 𑀲𑀻𑀔𑀹𑀦𑁅𑀪𑀯𑀦𑁆𑀢𑀻", "brahmi", "iast"));
    var urlEngine = "<?php echo base_url() ?>aksharamukha/index.php";
	var barma_temp = "";
    function getSrcTxtval() {
        return $("#src-txt").val();
    }
    function getDestTxtVal() {
        return $("#dest-txt").val();
    }
    function getSrcTypeText() {
        return $("#src-type option:selected").text();
    }
    function getDestTypeText() {
        return $("#dest-type option:selected").text();
    }
    function getSrcTypeval() {
        return $("#src-type").val();
    }
    function getDestTypeVal() {
        return $("#dest-type").val();
    }

    function getLang() {
        return $("#lang").val();
    }

    function lojulaPali(txt) {
        //เปลี่ยน ḷ เป็น  ḻ กรณีเป็นภาษาบาลี
        if (getLang() === "pali") {
            txt = txt.replace("ḷ", "ḻ");
        }
        return txt;
    }


    function transliteration() {
        var output = "";
        var txt = getSrcTxtval();
        var srcType = getSrcTypeval();
        var destType = getDestTypeVal();

        txt = lojulaPali(txt);

        if (srcType === 'thai') {
            /*thaisanscript.js*/
            txt = thaisanscript(txt);
            srcType = 'iast';
        } else if (srcType === 'lao') {
            txt = laosanscript(txt);
            srcType = 'iast';
        }

        if (srcType === 'iast') {
            txt = txt.toLowerCase();
        }

        output = Sanscript.t(txt, srcType, destType);
        console.log("txt : " + txt);
        console.log("srcType : " + srcType);
        console.log("destType : " + destType);
        console.log("output : " + output);
        return output;
    }

    function transliteToBackend() {
        var output = "";
        var txt = getSrcTxtval();
        var srcType = getSrcTypeval();
        txt = lojulaPali(txt);

        if (srcType === 'thai') {
            /*thaisanscript.js*/
            output = thaisanscript(txt);
        } else if (srcType === 'lao') {
            /*thaisanscript.js*/
            output = laosanscript(txt);
        } else {
            output = Sanscript.t(txt, srcType, 'iast');
        }
        return output;
    }
</script>

