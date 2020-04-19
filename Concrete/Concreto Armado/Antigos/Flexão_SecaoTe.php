<?php

echo "-----------ENGPACK CONCRETE ANALYSIS-----------\n";
echo "Flexão Simples - Seção Tê\n";
echo "-----------------------------------------------\n\n";

//Entrada--------------------------------------------------------//
$fck = 30; //MPa;
$fyk = 500; //MPa
$bw = 18; //cm
$bf = 45; //cm
$h = 30; //cm
$hf = 7; //cm
$cob = 2.5; //cm
$phiestribo = 5; //mm
$d = 25; //cm (essa é a variável do incremento 0.8h<=d<=0.9h)
$md = 112; //kN.m
//--------------------------------------------------------------//

echo "DADOS DE ENTRADA:\n\n";
echo "fck = $fck MPa\n";
echo "fyk = $fyk MPa\n";
echo "bw = $bw cm\n";
echo "h = $h cm\n";
echo "bf = $bf cm\n";
echo "hf = $hf cm\n";
echo "cob = $cob cm\n";
echo "d = $d cm\n";
echo "Φe = $phiestribo mm\n";
echo "-----------------------------------------------\n\n";

$fcd = $fck / 1.4;
$fyd = $fyk / 1.15;
$dlinha = $cob + ($phiestribo / 10) + (1.0 / 2); //cm
$ac = ($bf - $bw) * $hf + ($bw * $h); //cm²
$ycgsup = (($bf - $bw) * (0.5 * pow($hf, 2)) + (0.5 * $bw * pow($h, 2)))/$ac;
$ycginf = $h - $ycgsup;
$inercia = ((($bf - $bw) * pow($hf, 3))/12) + (($bw * pow($h, 3))/12) + ($bf - $bw) * $hf * pow($ycgsup - 0.5 * $hf, 2) + $bw * $h * pow($ycgsup - 0.5 * $h, 2);
$w = $inercia/$ycginf;

if ($fck <= 50) {
    $lambda = 0.8;
    $alfac = 0.85;
    $epsiloncu = 0.0035;
    $duct = 0.45;
    $fctm = 0.30*pow($fck, (2/3)); //MPa
} elseif ($fck > 50 && $fck <= 90) {
    $lambda = 0.8 - ($fck - 50)/400;
    $alfac = 0.85 * (1 - ($fck - 50)/200);
    $epsiloncu = 0.0026+0.035*pow((90-$fck)/100, 4);
    $duct = 0.35;
    $fctm = 2.12*log(1+0.11*$fck); //MPa
}

$fctksup = 1.3*$fctm; //MPa

$kmd = $md * 100 / ($bf * pow($d, 2)* ($fcd/10));
$kx = (1 - sqrt(1-(2*$kmd)/$alfac))/$lambda;
$kz = (1-0.5*$lambda*$kx);
$x = $kx * $d;
$y = $lambda * $x;

$lim23 = $epsiloncu / ($epsiloncu + 0.01);
$lim34 = $epsiloncu / ($epsiloncu + 0.00207);

if ($kx < $lim23) {
    $dominio = "Domínio 2";
    $armadura = "SIMPLES";
} elseif ($kx >= $lim23 && $kx <= $duct) {
    $dominio = "Domínio 3";
    $armadura = "SIMPLES";
} else {
    echo "Limite de ductilidade excedido (βx = x/d > $duct. Dimensionamento realizado como armadura dupla.\n"; //WARNING deixando o usuário ciente antes de realizar o dimensionamento, dando opção de continuar ou cancelar
    $dominio = "Domínio 3";
    $armadura = "DUPLA";
}

$mdmin = 0.8 * $w * $fctksup/10; //kN.cm
$kmdmin = $mdmin / ($bw * pow($d, 2)* ($fcd/10));
$kxmin = (1 - sqrt(1-(2*$kmdmin)/$alfac))/$lambda;
$kzmin = (1-0.5*$lambda*$kxmin);

$asmin = $mdmin/($kzmin*$d*$fyd/10); //cm²

$ro = ($asmin/$ac)*100; //%

if ($ro < 0.15) {
    $asmin = $ac * 0.15/100; //cm²
} else {
    $asmin = $asmin; //cm²
}

$asmax = $ac * 4/100; //cm²

if ($armadura == "SIMPLES" && $hf >= $y) {
    $ascalc = ($md*100)/($kz*$d*$fyd/10); //cm²
    $as2 = 0;
    if ($ascalc < $asmin) {
        $as1 = $asmin; //cm²
    } else {
        $as1 = $ascalc; //cm²
    }
} elseif ($armadura == "SIMPLES" && $hf < $y) {
    $mmesa = $alfac * ($fcd/10) * ($hf) * ($bf - $bw) * ($d - 0.5 * $hf); //kN.cm
  $malma = ($md * 100) - $mmesa; //kN.cm
  $ascalc = ($mmesa / (($d - 0.5 * $hf) * ($fyd/10))) + ($malma / (($d - 0.5 * $y) * ($fyd/10)));
    $as2 = 0;
    if ($ascalc < $asmin) {
        $as1 = $asmin; //cm²
    } else {
        $as1 = $ascalc; //cm²
    }
} elseif ($armadura == "DUPLA" && $hf >= $y) {
    $xlim = $duct * $d; //cm
  $zlim = $d - 0.5 * $lambda * $xlim; //cm
  $mlim = $bf * $alfac * ($fcd/10) * $lambda * $xlim * $zlim; //kN.cm
  $m2 = ($md*100) - $mlim; //kN.cm
  $as1 = $mlim / ($zlim * $fyd/10); //cm²
  $as2 = $m2 / (($d - $dlinha) * ($fyd/10)); //cm²
} else {
    $mmesa = $alfac * ($fcd/10) * ($hf) * ($bf - $bw) * ($d - 0.5 * $hf); //kN.cm
  $malma = $alfac * ($fcd/10) * $bw * $lambda * pow($d, 2) * ($duct - 0.5 * $lambda * pow($duct, 2)); //kN.cm
  $mlim = $mmesa + $malma;
    $m2 = ($md*100) - ($mlim);
    $amesa = ($bf - $bw) * $hf;
    $aalma = ($bw * $y);
    $acompr = $amesa + $aalma;
    $zt = ($amesa * ($d - 0.5 * $hf) + $aalma * ($d * (1 - 0.5 * $lambda * $duct)))/$acompr;
    $as1 = $mlim / ($zt * ($fyd/10));
    $as2 = $m2 / (($d - $dlinha) * ($fyd/10));
}

$astot = $as1 + $as2; //cm²

if ($astot > $asmax) {
    echo "(As,total = $astot cm²) EXCEDE o valor da área de aço máxima (As,máx = $asmax cm²).\n"; //Informar que a armadura ultrapassa o valor máximo
}

if ($h <= 60) {
    $aspele = 0;
} else {
    $aspele = 0.10 * $ac / 100; //cm²
}
echo "RESULTADOS:\n\n";
echo "RESULTADOS:\n\n";
if ($armadura == "DUPLA") {
    echo "x = $xlim cm \n";
    echo "βx = x/d = $duct \n";
} else {
    echo "x = $x cm \n";
    echo "βx = x/d = $kx \n";
}
echo "$dominio\n";
echo "Asmín = $asmin cm²\n";
echo "Asmáx = $asmax cm²\n";
echo "As = $as1 cm²\n";
echo "As' = $as2 cm²\n";
echo "As,pele = $aspele cm²\n\n";

//Vetores com as bitolas para CA50
$bit50 = array(5, 6.3, 8.0, 10.0, 12.5, 16.0, 20.0, 25.0, 32.0, 40.0); //mm
$tam50 = count($bit50);

$dmax = 1.9; //cm
$dauxh = 1.2 * $dmax; //cm
$dauxv = 0.5 * $dmax; //cm
$bdisp = $bw - 2 * ($cob + $phiestribo/10);


//Armadura As
echo "---------------------------------------------\n";
echo "Bitolas para As = $as1 cm²\n";
echo "---------------------------------------------\n\n";

for ($i = 0; $i < $tam50; $i++) {
    $phi = $bit50 [$i]/10;
    $asphi = (pi() * pow($phi, 2))/4;
    $n = ceil($as1/$asphi);

    echo "$n Φ ", $phi*10, "\n";

    $phil = $phi + 0.04 * $phi;
    $ah = 0;
    $av = 0;
  
    //espaçamento horizontal
    if ($dauxh >= 2) {
        $ah = $dauxh;
    } else {
        $ah = 2;
    }
    if ($ah <= $phil) {
        $ah = $phil;
    }
 
    //espaçamento vertical
    if ($dauxv >= 2) {
        $av = $dauxv;
    } else {
        $av = 2;
    }
    if ($av <= $phil) {
        $av = $phil;
    }

    //Número máximo de barras por camada;
    $nmax = floor(($bdisp + $ah)/($phil + $ah));

    //Critério de número mínimo por camada (Quando for desenhar as bitolas, utilizar o valor do nbar)
    if ($n == 1) {
        $nbar = 2;
    } else {
        $nbar = $n;
    }

    //Número de camadas necessárias
    $ncam = ceil($nbar/$nmax);
    echo "Número de Camadas = $ncam\n";
  
    if ($nbar == 2 && $ncam <> 1) {
        echo "Espaço insuficiente para alojar a armadura.\n\n"; //Mensagem para o usuário caso a bitola selecionada do droplist não atenda a verificação
    } else {
        //Centróide da Armadura
        $numerador = 0;
        $denominador = 0;
        $y1 = $cob + ($phiestribo/10) + ($phi/2);
  
        for ($j=1; $j <= $ncam; $j++) {
            if ($nbar >= $nmax) {
                $nj = $nmax;
            } else {
                $nj = $nbar;
            }
    
            $nbar = $nbar - $nj;

            $ycam = $y1 + ($j-1) * ($av + $phi);
            $numerador = $numerador + ($asphi * $nj * $ycam);
            $denominador = $denominador + ($asphi * $nj);
        }

        $ycgarmadura = $numerador / $denominador; //Se possível colocá-lo do desenho, cotado de baixo para cima
        $dreal = $h - $ycgarmadura;
  
        echo "Ycg_armadura = $ycgarmadura cm \n";
        echo "dreal = $dreal cm \n";

        $ateste = $ycgarmadura - $y1;
    
        if ($dreal >= $d && $ateste <= 0.1*$h) {
            echo "OK!\n\n";
        } else {
            if ($dreal < $d) {
                echo "NÃO OK: dreal = $dreal cm < d = $d cm.\n";
            }
            if ($ateste > 0.1*$h) {
                $aux = 0.1 * $h;
                echo "NÃO OK: $ateste cm > 10% h = $aux cm.\nA armadura não pode ser considerada concentrada.\n";
            }
            echo "\n";
        }
    }
}

//Armadura As2
if ($as2 <> 0) {
    echo "---------------------------------------------\n";
    echo "Bitolas para As2 = $as2 cm²\n";
    echo "---------------------------------------------\n\n";

    for ($i = 0; $i < $tam50; $i++) {
        $phi = $bit50 [$i]/10;
        $asphi = (pi() * pow($phi, 2))/4;
        $n = ceil($as2/$asphi);

        echo "$n Φ ", $phi*10, "\n";
  
        $phil = $phi + 0.04 * $phi;
        $ah = 0;
        $av = 0;
    
        //espaçamento horizontal
        if ($dauxh >= 2) {
            $ah = $dauxh;
        } else {
            $ah = 2;
        }
        if ($ah <= $phil) {
            $ah = $phil;
        }

        //espaçamento vertical
        if ($dauxv >= 2) {
            $av = $dauxv;
        } else {
            $av = 2;
        }
        if ($av <= $phil) {
            $av = $phil;
        }

        //Número máximo de barras por camada;
        $nmax = floor(($bdisp + $ah)/($phil + $ah));

        //Critério de número mínimo de barras por camada (Quando for desenhar as bitolas, utilizar o valor do nbar)
        if ($n == 1) {
            $nbar = 2;
        } else {
            $nbar = $n;
        }

        //Número de camadas necessárias
        $ncam = ceil($nbar/$nmax);
        echo "Número de Camadas = $ncam\n";
      
        if ($nbar == 2 && $ncam <> 1) {
            echo "Espaço insuficiente para alojar a armadura.\n\n"; //Mensagem para o usuário caso a bitola selecionada do droplist não atenda a verificação
        } else {
            //Centróide da Armadura
            $numerador = 0;
            $denominador = 0;
            $y1 = $cob + ($phiestribo/10) + ($phi/2);
    
            for ($j=1; $j <= $ncam; $j++) {
                if ($nbar >= $nmax) {
                    $nj = $nmax;
                } else {
                    $nj = $nbar;
                }
      
                $nbar = $nbar - $nj;

                $ycam = $y1 + ($j-1) * ($av + $phi);
                $numerador = $numerador + ($asphi * $nj * $ycam);
                $denominador = $denominador + ($asphi * $nj);
            }

            $ycgarmadura = $numerador / $denominador;
            $dreal = $h - $ycgarmadura;
    
            echo "Ycg_armadura = $ycgarmadura cm \n"; //Se possível colocá-lo do desenho, cotado de cima para baixo
            echo "dreal = $dreal cm \n";

            $ateste = $ycgarmadura - $y1;

            if ($dreal >= $d && $ateste <= 0.1*$h) {
                echo "OK!\n\n";
            } else {
                if ($dreal < $d) {
                    echo "NÃO OK: dreal = $dreal cm < d = $d cm.\n";
                }
                if ($ateste > 0.1*$h) {
                    $aux = 0.1 * $h;
                    echo "NÃO OK: $ateste cm > 10% h = $aux cm.\nA armadura não pode ser considerada concentrada.\n";
                }
                echo "\n";
            }
        }
    }
}
echo "\n\n";
