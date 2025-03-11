<html><script type="text/javascript">
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
</script>
</html>
<?php
// On d�marre la session
session_start ();

// On d�truit les variables de notre session
session_unset ();

// On d�truit notre session
$_SESSION["user"] = NULL;
$_SESSION["username"] = NULL;
$_SESSION["password"] = NULL;


session_destroy ();

if (isset($_COOKIE['remember_user'])) {
    unset($_COOKIE['remember_user']);
    setcookie('remember_user', '',  time() - 3600);
//    return true;

} else {
//    return false;
}
// On redirige le visiteur vers la page d'accueil
header ('location: index.php');
?>

