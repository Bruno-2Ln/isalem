//fichier de programmation du graphique contenu dans result.php ayant pour but
//de faire apparaître l'axe des abscisses et des ordonnées ainsi qu'un point représantant
//la position de l'utilisateur dans ce graphique à la vue des réponses données plus tôt. 

document.addEventListener('DOMContentLoaded', () => {
//---Création du graphique

//récupération de la balise canvas afin de créer le graphique
let graphic = document.getElementById("graphic")
let context = graphic.getContext("2d")


//fonction permettant de tracer des lignes dans le canvas
function drawLine(x1, y1, x2, y2, lineWidth, color){
    context.beginPath()
    context.moveTo(x1,y1)
    context.lineTo(x2,y2)
    context.lineWidth = lineWidth
    context.strokeStyle = color
    context.stroke()
}

//ici est tracé l'axe des abscisses et des ordonnées du graphique
drawLine(190, 0, 190, 380, 2, "black")
drawLine(0, 190, 380, 190, 2, "black")

//ici en utilisant la fonction drawline il est effectué une graduation
//à l'echelle 5px pour 1mm
//graduation de l'axe horizontal
let a = 10
for (let i=0; i<73; i++){
    drawLine(a,190,a,195, 1,"black")
    a += 5
}
//puis vertical
let b = 370
for (let i=0; i<73; i++){
    drawLine(190,b,185,b, 1,"black")
    b -= 5
}

//afin d'obtenir tous les 5mm un repère de graduation visible
// 2 fonctions permettent en partant du point central
//de définir ces repères
//les paramètres : 
//1 - Correspond au nombre de graduation en partant du point central
//2 - Le point de départ de la ligne par rapport au bord gauche du canvas
//et le point d'arrivée par rapport au bord gauche.
//3 - Le point de départ de la ligne par rapport au bord haut du canvas
//4 - Le point d'arrivée de la ligne par rapport au bord haut du canvas
//5 - La différence en pixels apportée à chaque tour au 2ème paramètre de la fonction
function graduationX(tour, departX, y1, y2, diff){
    for (let i=0; i<tour; i++){
        //Pour chaque index divisé par 5 il reste 0, une ligne est tracée
        if (i%5 === 0){
            drawLine(departX, y1, departX, y2, 1, "black")
        }
        departX += diff
    }
}
//les paramètres : 
//1 - Correspond au nombre de graduation en partant du point central
//2 - Le point de départ de la ligne par rapport au bord gauche du canvas
//3 - Le point de départ de la ligne par rapport au bord haut du canvas
//et le point d'arrivée par rapport au bord haut
//4 - Le point d'arrivée de la ligne par rapport au bord gauche du canvas
//5 - La différence en pixels apportée à chaque tour au 3ème paramètre de la fonction   
    function graduationY(tour, x1, departY, x2, diff){
        for (let i=0; i<tour; i++){
        if (i%5 === 0){
            drawLine(x1, departY, x2, departY, 1, "black")
        }
        departY += diff;
    }
}

graduationX(36,190,190,200,5)
graduationX(36,190,190,200,-5)

graduationY(36,190,190,180,5)
graduationY(36,190,190,180,-5)


//----Création du point sur le graphique

//récupération des valeurs de x et y importées par $_SESSION dans result.php
//ces valeurs vont être utilisées afin de créer le point de référence de l'utilisateur
//sur le graphique
let spanX = document.getElementById("x")
let spanY = document.getElementById("y")
//afin d'être à l'échelle du canvas (5px = 1mm), les valeurs de x et y sont multipliées par 5.
//le point x partant du bord gauche, il est additionné
//à la moitié du canvas
let xValue = 190 + (parseInt(spanX.textContent)*5) 
//le point y partant du haut du canvas mais l'axe des ordonnées
//partant de bas vers le haut, ici la logique s'inverse, y est soustrait à 190.
let yValue = 190 - (parseInt(spanY.textContent)*5)
//..Maintenant yValue et xValue ont été converties afin d'être placées d'après les règles imposées
//par canvas tout en donnant à l'utilisateur une vision précise de son point sur le graphique
//d'après ses résultats.

context.beginPath()
context.lineWidth = "2"
context.fillStyle = "red"
context.arc(xValue,yValue,4,0,2*Math.PI)
context.fill()


//insertion de texte indiquant où sur le graphique se situe les 4 résultats possibles.

//fonction pour l'insertion de texte sur la canvas
//3 paramètres : le texte, la distance du texte par rapport au bord gauche du canvas
//et la distance par rapport au bord haut.
function writeTextInCanvas(text,left,top){
    context.font = "bold 12px Verdana, Arial, serif"
    context.fillStyle = "black"
    context.fillText(text,left,top)

}

writeTextInCanvas("INTUITIF",55,20)
writeTextInCanvas("REFLEXIF",53,35)

writeTextInCanvas("METHODIQUE",240,20)
writeTextInCanvas("REFLEXIF",252,35)

writeTextInCanvas("METHODIQUE",240,350)
writeTextInCanvas("PRAGMATIQUE",236,365)

writeTextInCanvas("INTUITIF",55,350)
writeTextInCanvas("PRAGMATIQUE",35,365)

})