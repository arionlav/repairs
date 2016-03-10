<?
if ($class == 'errorLogin') {
    foreach ($errors->all() as $error) {
        if (!empty($arr)) {
            foreach ($arr as $a) {
                if ($a === $error) {
                    continue 2;
                }
            }
        }
        $arr[] = $error;
        echo "<p class='error {$class}'>{$error}</p>";
    }
} elseif ($class == 'errorRegister') {
    foreach ($errors as $error) {
        echo "<p class='error {$class}'>{$error}</p>";
    }
} elseif ($class == 'errorComment') {
    foreach ($errors->all() as $error) {
        echo "<p class='error'>{$error}</p>";
    }
}
