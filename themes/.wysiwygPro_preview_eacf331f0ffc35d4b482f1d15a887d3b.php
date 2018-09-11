<?php
if ($_GET['randomId'] != "RvmEuQcaWybqOM0JXzgRcytur2Xw_WB1wcnY4bUyM40I4uANaRpT5CXdgGJ2kr02") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
