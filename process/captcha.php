<?php
session_start();
header('Content-Type: image/png');
$largeur=80;
$hauteur=25;
$lignes=10;
$caracteres="ABCDEF0123456789";
//création d'une image noire 
$image = imagecreatetruecolor($largeur, $hauteur);

//création d'un rectangle dans $image
imagefilledrectangle($image, 0, 0, $largeur, $hauteur, imagecolorallocate($image, 255, 255, 255));

//va renvoyer un tableau donc les 3 clés r, g et b seront la convertion de 2 caractères (représentant un nombre héxadécimal)
//en nombre décimal
function hexargb($hex) {
    return array("r"=>hexdec(substr($hex,0,2)),"g"=>hexdec(substr($hex,2,2)),"b"=>hexdec(substr($hex,4,2)));
}

//la boucle va permettre de tracer 10 lignes aléatoires dans $image, la couleur sera aléatoire par la fonction
//hexargb()
for($i=0;$i<=$lignes;$i++){
    //$rgb va contenir les caractères de l'index 0 à 6 de la chaîne de caractère notée mélangée aléatoirement
    $rgb = hexargb(substr(str_shuffle("ABCDEF0123456789"),0,6));
    imageline($image,rand(1,$largeur-25),rand(1,$hauteur),rand(1,$largeur+25),rand(1,$hauteur),imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']));
}

//captcha aléatoire, la chaîne des caractères possibles au code est mélangée, puis on récupère de l'index 0 à 4 les caractères
$code1=substr(str_shuffle($caracteres),0,4);
//le captcha est enregistré dans $_SESSION
$_SESSION['captcha']=$code1;
//$code va être la chaîne de caractère qui apparaîtra sur le formulaire
$code="";

//$code détient la même chaîne de caractère que $code1 sauf que chaque caractère est séparé d'un espace
//rendant le code à taper plus clair côté formulaire
for($i=0;$i<=strlen($code1);$i++){
    $code .=substr($code1,$i,1)." ";
}
//permet de dessiner la chaîne de caractères $code
imagestring($image, 5, 10, 5, $code, imagecolorallocate($image, 0, 0, 0));
imagepng($image);
imagedestroy($image);
?>
