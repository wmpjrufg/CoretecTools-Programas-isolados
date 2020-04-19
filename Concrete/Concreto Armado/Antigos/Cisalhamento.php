<?php

echo "-----------ENGPACK CONCRETE ANALYSIS-----------\n";
echo "Cisalhamento\n";
echo "-----------------------------------------------\n\n";

//Entrada-------------------------------------------------------------
$fck = 30; //MPa;
$aco = "CA50"; //Dar opção em um droplist contendo CA50 e CA60
$bw = 20; //cm
$h = 85; //cm
$cob = 3; //cm
$d = 76.4; //cm (essa é a variável do incremento 0.8h<=d<=0.9h)
$l = 1200; //cm
$vsd = 168; //kN
$nramos = 2; //colocar droplist com os valores 2, 3 e 4.
//--------------------------------------------------------------------

echo "DADOS DE ENTRADA:\n\n";
echo "fck = $fck MPa\n";
echo "Aço $aco \n";
echo "bw = $bw cm\n";
echo "h = $h cm\n";
echo "cob = $cob cm\n";
echo "d = $d cm\n";
echo "L = $l m\n";
echo "Nº ramos = $nramos \n";
echo "-----------------------------------------------\n\n";


if ($aco == "CA50") {
    $fyk = 500; //MPa
} elseif ($aco == "CA60") {
    $fyk = 600; //MPa
}

if ($fck <= 50) {
    $fctm = 0.30*pow($fck, (2/3)); //MPa
} elseif ($fck > 50 && $fck <= 90) {
    $fctm = 2.12*log(1+0.11*$fck); //MPa
}

$fctinf = 0.7 * $fctm; //MPa
$fcd = $fck / 1.4;
$fyd = $fyk / 1.15;
$fctd = $fctinf / 1.4;

if ($fyd < 435) {
    $fywd = $fyd;
} else {
    $fywd = 435; //MPa
}

$alfav2 = 1 - ($fck/250);
$vrd2 = 0.27 * $alfav2 * ($fcd/10) * $bw * $d; //kN

if ($vsd > $vrd2) {
    echo "Ocorrerá esmagamento das bielas comprimidas! Para seguir com o dimensionamento, faça alterações nas propriedades da seção e/ou do material.\n"; //Warning que interrompe o dimensionamento
}

$aswmin = 20 * ($fctm / $fyk) * $bw; //(cm²/m)

$vc = 0.60 * ($fctd / 10) * $bw * $d;

$vsw = $vsd - $vc;

if ($vc >= $vsd) {
    $aswfinal = $aswmin;
} else {
    $asw = ($vsw / (0.9 * $d * ($fywd / 10))) * 100;

    if ($asw <= $aswmin) {
        $aswfinal = $aswmin;
    } else {
        $aswfinal = $asw;
    }
}

echo "RESULTADOS:\n\n";
echo "VRd2 = $vrd2 kN\n";
echo "Vc = $vc kN\n";
echo "Vsw = $vsw kN\n";
echo "Asw,mín = $aswmin cm²/m \n";
echo "Asw,90 = $aswfinal cm²/m \n";
echo "-----------------------------------------------\n\n";

//Limite da bitola
$phimin = 5; //mm (valor fixo)
$phimax = ($bw * 10) / 10; //mm

//Espaçamento mínimo entre estribos
$phivibrador = 4; //cm (valor fixo)
$smin = $phivibrador + 1; //cm

//Espaçamento máximo entre estribos:
if ($vsd <= 0.67 * $vrd2) {
    $saux = 0.6 * $d;
    if ($saux <= 30) {
        $smax = $saux;
    } else {
        $smax = 30;
    }
} else {
    $saux = 0.3 * $d;
    if ($saux <= 20) {
        $smax = $saux;
    } else {
        $smax = 20;
    }
}

//Espaçamento Máximo entre ramos do estribo
if ($vsd <= 0.20 * $vrd2) {
    $staux = $d;
    if ($staux <= 80) {
        $stmax = $staux;
    } else {
        $stmax = 80;
    }
} else {
    $staux = 0.6 * $d;
    if ($staux <= 35) {
        $stmax = $staux;
    } else {
        $stmax = 35;
    }
}

// Vetores com as bitolas para CA50 e CA60
$bit50 = array(5.0, 6.3, 8.0, 10.0, 12.5, 16.0);
$tam50 = count($bit50);

$bit60 = array(5.0, 6.0, 7.0, 8.0, 9.5);
$tam60 = count($bit60);

if ($aco == "CA50") {
    for ($i = 0; $i < $tam50; $i++) {
        $phie = $bit50 [$i];
        if ($phie > $phimax) {
            echo "Φe = $phie mm > Φemáx = $phimax mm \n"; //Aparecer em vermelho
        } else {
            $sramo = ($bw - 2 * $cob - $nramos * ($phie / 10)) / ($nramos - 1); //cm
            if ($sramo > $stmax) {
                echo "Espaçamento entre ramos = $sramo cm MAIOR do que o espaçamento máximo entre ramos = $staux cm. Para seguir com o dimensionamento, aumente o números de ramos do estribo.\n\n"; //Warning parando o dimensionamento
            } else {
                $asphie = ($nramos * pi() * pow($phie/10, 2))/4;
                $s = ($asphie / $aswfinal)*100;
                if ($s < $smin) {
                    $sfinal = round($smin, 1);
                } elseif ($s > $smax) {
                    $sfinal = round($smax, 1);
                } else {
                    $sfinal = round($s, 1);
                }
                $nestribos = round(($l / $sfinal), 0);
                echo "$nestribos Φ $phie espaçados a cada $sfinal cm c/ $nramos ramos \n"; //Dado de saída
            }
        }
    }
} else {
    for ($i = 0; $i < $tam60; $i++) {
        $phie = $bit60 [$i];
        if ($phie > $phimax) {
            echo "Φe = $phie mm > Φemáx = $phimax mm \n"; //Aparecer em vermelho
        } else {
            $sramo = ($bw - 2 * $cob - $nramos * ($phie / 10)) / ($nramos - 1); //cm
            if ($sramo > $stmax) {
                echo "Espaçamento entre ramos = $sramo cm MAIOR do que o espaçamento máximo entre ramos = $staux cm. Para seguir com o dimensionamento, aumente o números de ramos do estribo\n\n"; //Warning parando o dimensionamento
            } else {
                $asphie = ($nramos * pi() * pow($phie/10, 2))/4;
                $s = ($asphie / $aswfinal)*100;
                if ($s < $smin) {
                    $sfinal = round($smin, 1);
                } elseif ($s > $smax) {
                    $sfinal = round($smax, 1);
                } else {
                    $sfinal = round($s, 1);
                }
                $nestribos = round(($l / $sfinal), 0);
                echo "$nestribos Φ $phie espaçados a cada $sfinal cm c/ $nramos ramos \n"; //Dado de saída
            }
        }
    }
}
