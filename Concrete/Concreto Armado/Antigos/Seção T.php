<!DOCTYPE html>
<link rel="stylesheet" href="_css/estilo.css"/>
<meta charset="UTF-8"/>
<title>Curso de PHP - CursoemVideo.com</title>
<body>
<div>
    <?php
        // Esforços
        echo "--------------ESFORÇOS NA SEÇÃO--------------\n\n";
        $mk = 10000/1.4; // kN.m
        echo "</br> mk = $mk kN.m";
        $md = $mk * 140; // kN.cm
        echo "</br>Md = $md kN.cm </br>";

        //propriedades
        echo "----------PROPRIEDADES DO MATERIAL----------\n\n";
        $fck = 30; // MPa
        echo "</br> fck = $fck MPa";
        $fcd = $fck * 0.1 / 1.4; // kN/cm²
        echo "</br> fcd = $fcd kN/cm²";
        $aco = "CA50";
        echo "</br> Aço $aco";


        $aco = "CA50";
        if ($aco == "CA50") {
            $fyk = 500; //MPa
        } elseif ($aco == "CA60") {
            $fyk = 600; //MPa
        }
        echo "</br>fyk = $fyk MPa";
        $fyd = $fyk * 0.1 / 1.15; // kN/cm²
        echo "</br>fyd = $fyd kN/cm²</br>";

        //coeficientes alfac e lambda
        echo "----------COEFICIENTES ALFAC E LAMBDA----------\n\n";
        if ($fck<=50) {
            $alfac = 0.85;
            $lim23=0.259;
            $lim34=0.6283;

            echo "</br> alfac = $alfac";
            $lambda = 0.8;
            echo "</br> lambda = $lambda </br>";
        } else {
            $alfac = .85*(1-($fck-50)*(1/200));
            echo "</br> alfac = $alfac";
            $lambda = .8-($fck-50)*(1/400);
            echo "</br> lambda = $lambda </br>";
            $epsiloncu=0.0026+0.035*((90-$fck)/100)**4;
            $lim23= $epsiloncu/($epsiloncu+0.01);
            $lim34= $epsiloncu/($epsiloncu+0.00207);
        }
        echo "</br> limite entre os dominios 2 e 3 = $lim23 </br>";
        echo "limite entre os dominios 3 e 4 = $lim34 </br>";

        // dados da seção
        echo "------------PROPRIEDADES DA SEÇÃO------------\n\n";
        $hf = 20; //cm
        echo "</br> hf = $hf cm";
        $bw = 18; //cm
        echo "</br> bw = $bw cm";
        $bf = 170; //cm
        echo "</br> bf = $bf cm";
        $d = 175; //cm
        echo "</br> d = $d cm";
        $d1 = 5; //cm
        echo "</br> d1 = $d1 cm";
        $h = $d+ $d1; //cm
        echo "</br> h = $h cm</br>";

        //aleff
        $cob = 3; //cm
        $phiestribo = 6.3; //mm
        echo "------------VERIFICAÇÃO DA SEÇÃO T------------\n\n";

        //POSIÇÃO DA LN SUPONTO QUE PASSE NA MESA DA VIGA bw=bf
        $bwteste = $bf;
        echo "</br> bw = bf = $bwteste cm";
        echo "</br> md = $md kN.cm";
        echo "</br> d = $d cm";
        echo "</br> fcd = $fcd kN/cm²";
        $kmd = $md/($bwteste*$d**2*$fcd);
        echo "</br> kmd = $kmd";
        $kx = (1-sqrt(1-2*$kmd/$alfac))/$lambda;
        echo "</br> kx = $kx";
        $x = $kx*$d;
        echo "</br> x = $x cm</br>";
        $mdlim = .251*$bw*$d**2*$fcd;
        $ASpe= 2*pi()*.8**2*(1/4);

        //ARMADURA
        if ($x<$hf) {
            $T = "FALSO";
            $M1 = $md;
            $AS2 = 0;
            $AS22 = 0;
            $AStotalcomp = $ASpe;
            $mdlim = .251*$bwteste*$d**2*$fcd;
            if ($md<$mdlim) {
                $armadura = "SIMPLES";
            }
            $KZ = 1-(1/2)*$lambda*$kx;
            $AS1 = $md/($KZ*$d*$fyd);
            if ($kx<$lim23) {
                $dominio=2;
            } elseif ($kx>=$lim23 && $kx<$lim34) {
                $dominio=3;
            } else {
                $dominio = 4;
            }
        } else {
            $T = "VERDADEIRO";
            $M1 = .85*$fcd*$hf*($bf-$bw)*($d-(1/2)*$hf);
            $AS1 = $M1/(($d-(1/2)*$hf)*$fyd);
            $M2 = $md- $M1;
            if ($M2<$mdlim) {
                $armadura = "SIMPLES";
                $AS22 = 0;
                $AStotalcomp = $ASpe;
            } else {
                $armadura = "dupla";
                $M2comp = $M2 - $mdlim;
                $M2 = $mdlim;
                $AS22 = $M2comp/(($d-$d1)*$fyd);
                $AStotalcomp = $AS22;
            }
            $kmd = $M2/($bw*$d**2*$fcd);
            $kx2 = (1-sqrt(1-2*$kmd/$alfac))/$lambda;
            if ($kx2<.259) {
                $dominio=2;
            } elseif ($kx2>=0.259 && $kx2<0.6283) {
                $dominio=3;
            } else {
                $dominio = 4;
            }
            $KZ = 1-(1/2)*$lambda*$kx2;
            $AS2 = $M2/($KZ*$d*$fyd);
        }
        //ARMADURA DE AÇO LONGITUDINAL
        echo "------------ARMADURA DE AÇO LONGITUDINAL------------\n\n";

        echo "</br> T = $T";
        echo "</br> Dominio = $dominio";
        echo "</br> armadura = $armadura</br>";

        //CONTRIBUIÇÃO DA ALMA
        echo "------------CONTRIBUIÇÃO DA ALMA------------\n\n";

        echo "</br> M2 = $M2 kN.cm";
        echo "</br> As2 = $AS2 cm²";
        echo "</br> As22 = $AS22 cm²</br>";

        //CONTRIBUIÇÃO DA MESA

        echo "------------CONTRIBUIÇÃO DA MESA------------\n\n";

        echo "</br> M1 = $M1 kN.cm";
        echo "</br> As1 = $AS1 kN.cm</br>";

        //ÁREA TOTAL DE AÇO TRACIONADA
        echo "------------ÁREA TOTAL DE AÇO CALCULADA TRACIONADA------------\n\n";
        $dbarra=8;

        $AScalc = $AS1 + $AS2 + $AS22;
        echo "</br> AScalc = $AScalc cm²";
        $nbarra= ceil($AScalc/((pi()*($dbarra*0.1)**2)/4));
        echo "</br> Número de barras = $nbarra Φ$dbarra </br>";

        echo "-------------DESBITOLAR ÁREA DE AÇO TRACIONADA------------\n\n";


        //Parte 9 - Verificações
        echo "---------------------------------------------\n\n";

        //Vetores com as bitolas para CA50
        $bit50 = array(5, 6.3, 8.0, 10.0, 12.5, 16.0, 20.0, 25.0, 32.0, 40.0); //mm
        $tam50 = count($bit50);

        //valores auxiliares para os espaçamentos
        $dmax = 1.9; //cm (diâmetro máximo do agregado)
        $dauxh = 1.2 * $dmax; //cm
        $dauxv = 0.5 * $dmax; //cm

        //Espaço disponível para alocação das barras:
        $bdisp = $bw - 2 * ($cob + $phiestribo/10);
        echo "bdisp = $bdisp cm \n\n";

        echo "---------------------------------------------\n\n\n";

        echo "---------------------------------------------\n\n";
        echo "Bitolas para As1 = $AScalc cm²\n\n";
        echo "---------------------------------------------\n\n";

        for ($i = 0; $i < $tam50; $i++) {
            $phi = $bit50 [$i]/10;
            $asphi = (pi() * pow($phi, 2))/4;
            $n = ceil($AScalc/$asphi);

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
            echo "Ah = $ah cm\n";

            //espaçamento vertical
            if ($dauxv >= 2) {
                $av = $dauxv;
            } else {
                $av = 2;
            }
            if ($av <= $phil) {
                $av = $phil;
            }
            echo "Av = $av cm\n";

            //Número máximo de barras por camada;
            $nmax = floor(($bdisp + $ah)/($phil + $ah));

            echo "Número máximo de barras por camada = $nmax\n";

            //Critério de número mínimo por camada (Quando for desenhar, utilizar o valor do nbar)
            if ($n == 1) {
                $nbar = 2;
            } else {
                $nbar = $n;
            }

            //Número de camadas necessárias
            $ncam = ceil($nbar/$nmax);

            echo "Número de camadas = $ncam\n";

            if ($nbar == 2 && $ncam <> 1) {
                echo "Espaço insuficiente para alojar a armadura.\n\n\n";
            } else {
                //Centróide da Armadura
                $numerador = 0;
                $denominador = 0;
                $y1 = $cob + ($phiestribo/10) + ($phil/2);

                for ($j=1; $j <= $ncam; $j++) {
                    if ($nbar >= $nmax) {
                        $nj = $nmax;
                    } else {
                        $nj = $nbar;
                    }

                    $nbar = $nbar - $nj;

                    $ycam = $y1 + ($j-1) * $av;
                    $numerador = $numerador + ($asphi * $nj * $ycam);
                    $denominador = $denominador + ($asphi * $nj);
                }

                $ycgarmadura = $numerador / $denominador;
                $dreal = $h - $ycgarmadura;

                echo "Ycg_armadura = $ycgarmadura cm \n";
                echo "dreal = $dreal cm \n";

                $ateste = $ycgarmadura - $y1;

                echo "ateste = $ateste cm \n";

                if ($dreal >= $d && $ateste <= 0.1*$h) {
                    echo "OK!\n\n\n";
                } else {
                    if ($dreal < $d) {
                        echo "NÃO OK: dreal = $dreal cm < d = $d cm.\n";
                    }
                    if ($ateste > 0.1*$h) {
                        echo "NÃO OK: $ateste cm > ", 0.1*$h, " cm.\n";
                    }
                    echo "\n\n\n";
                }
            }
        }

        //        // Vetores com as bitolas para CA50 e CA60
//        $bit50 = array (6.3, 8.0, 10.0, 12.5, 16.0, 20.0, 25.0, 32.0, 40.0);
//        $tam50 = count ($bit50);
//        $bit60 = array (4.2, 5.0, 6.0);
//        $tam60 = count ($bit60);
//
//        if ($aco == "CA50"){
//            for ($i = 0; $i < $tam50; $i++){
//                $phi = $bit50 [$i];
//                $asphi = (pi() * pow ($phi/10, 2))/4;
//                $n = ceil ($AScalc/$asphi);
//                echo "$n Φ ";
//                print_r ($bit50 [$i]);
//                echo "\n";
//            }
//        }
//        elseif ($aco == "CA60"){
//            for ($i = 0; $i < $tam60; $i++){
//                $phi = $bit60 [$i];
//                $asphi = (pi() * pow ($phi/10, 2))/4;
//                $n = ceil ($AScalc/$asphi);
//                echo "$n Φ ";
//                print_r ($bit60 [$i]);
//                echo "\n";
//            }
//        }
//
//        //ESPAÇAMENTO ENTRE AS BARRAS TRACIONADAS
//        echo "------------ESPAÇAMENTO ENTRE AS BARRAS TRACIONADAS------------\n\n";
//
//        echo "</br>bitola adotada = Φ$dbarra";
//        $dmaxagr=20;
//        echo "</br>agredado adotado = Φ$dmaxagr mm";
//        $ah= max(20,$dbarra,1.2*$dmaxagr)/10;
//        echo "</br>espaçamento horizontal = $ah cm";
//        $av= max(20,$dbarra,0.5*$dmaxagr)/10;
//        echo "</br>espaçamento vertical = $av cm </br>";

        //ÁREA TOTAL DE AÇO COMPRIMIDA
        echo "------------ÁREA TOTAL DE AÇO CALCULADA COMPRIMIDA------------\n\n";
        $dbarrac=8;
        echo "</br> AScomp = $AStotalcomp cm²";
        $nbarrac= ceil($AStotalcomp/((pi()*($dbarrac*0.1)**2)/4));
        echo "</br> Número de barras = $nbarrac Φ$dbarrac </br>";

        echo "-------------DESBITOLAR ÁREA DE AÇO COMPRIMIDA------------\n\n";


        //VERIFICAÇÕES

        echo "---------------------------------------------\n\n";
        echo "Bitolas para As2 = $AStotalcomp cm²\n\n";
        echo "---------------------------------------------\n\n";

        for ($i = 0; $i < $tam50; $i++) {
            $phi = $bit50 [$i]/10;
            $asphi = (pi() * pow($phi, 2))/4;
            $n = ceil($AStotalcomp/$asphi);

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
            echo "Ah = $ah cm\n";

            //espaçamento vertical
            if ($dauxv >= 2) {
                $av = $dauxv;
            } else {
                $av = 2;
            }
            if ($av <= $phil) {
                $av = $phil;
            }
            echo "Av = $av cm\n";

            //Número máximo de barras por camada;
            $nmax = floor(($bdisp + $ah)/($phil + $ah));

            echo "Número máximo de barras por camada = $nmax\n";

            //Critério de número mínimo de barras por camada (Quando for desenhar, utilizar o valor do nbar)

            if ($n == 1) {
                $nbar = 2;
            } else {
                $nbar = $n;
            }

            //Número de camadas necessárias
            $ncam = ceil($nbar/$nmax);

            echo "Número de camadas = $ncam\n";

            if ($nbar == 2 && $ncam <> 1) {
                echo "Espaço insuficiente para alojar a armadura.\n\n\n";
            } else {
                //Centróide da Armadura
                $numerador = 0;
                $denominador = 0;
                $y1 = $cob + ($phiestribo/10) + ($phil/2);

                for ($j=1; $j <= $ncam; $j++) {
                    if ($nbar >= $nmax) {
                        $nj = $nmax;
                    } else {
                        $nj = $nbar;
                    }

                    $nbar = $nbar - $nj;

                    $ycam = $y1 + ($j-1) * $av;
                    $numerador = $numerador + ($asphi * $nj * $ycam);
                    $denominador = $denominador + ($asphi * $nj);
                }

                $ycgarmadura = $numerador / $denominador;
                $dreal = $h - $ycgarmadura;

                echo "Ycg_armadura = $ycgarmadura cm \n";
                echo "dreal = $dreal cm \n";

                $ateste = $ycgarmadura - $y1;

                echo "ateste = $ateste cm \n";

                if ($dreal >= $d && $ateste <= 0.1*$h) {
                    echo "OK!\n\n\n";
                } else {
                    if ($dreal < $d) {
                        echo "NÃO OK: dreal = $dreal cm < d = $d cm.\n";
                    }
                    if ($ateste > 0.1*$h) {
                        echo "NÃO OK: $ateste cm > ", 0.1*$h, " cm.\n";
                    }
                    echo "\n\n\n";
                }
            }
        }

//        // Vetores com as bitolas para CA50 e CA60
//        $bit50 = array (6.3, 8.0, 10.0, 12.5, 16.0, 20.0, 25.0, 32.0, 40.0);
//        $tam50 = count ($bit50);
//        $bit60 = array (4.2, 5.0, 6.0);
//        $tam60 = count ($bit60);
//
//        if ($aco == "CA50"){
//            for ($i = 0; $i < $tam50; $i++){
//                $phi = $bit50 [$i];
//                $asphi = (pi() * pow ($phi/10, 2))/4;
//                $n = ceil ($AStotalcomp/$asphi);
//                echo "$n Φ ";
//                print_r ($bit50 [$i]);
//                echo "\n";
//            }
//        }
//        elseif ($aco == "CA60"){
//            for ($i = 0; $i < $tam60; $i++){
//                $phi = $bit60 [$i];
//                $asphi = (pi() * pow ($phi/10, 2))/4;
//                $n = ceil ($AStotalcomp/$asphi);
//                echo "$n Φ ";
//                print_r ($bit60 [$i]);
//                echo "\n";
//            }
//        }
//
//        //ESPAÇAMENTO ENTRE AS BARRAS COMPRIMIDAS
//        echo "------------ESPAÇAMENTO ENTRE AS BARRAS COMPRIMIDAS------------\n\n";
//
//        echo "</br>bitola adotada = Φ$dbarrac";
//        echo "</br>agregado adotado = Φ$dmaxagr mm";
//        if ($ASpe>$AS22)
//            echo "</br>porta estribo = 2Φ$dbarrac em $bw cm </br>";
//        else{
//            $ahc= max(20,$dbarrac,1.2*$dmaxagr)/10;
//            echo "</br>espaçamento horizontal = $ahc cm";
//            $avc= max(20 ,$dbarrac,0.5*$dmaxagr)/10;
//            echo "</br>espaçamento vertical = $avc cm</br>";
//        }

        //ÁRMADURA DE PELE
        echo "------------ARMADURA DE PELE------------\n\n";

        if ($h>60) {
            echo "Como a altura é menor que 60 cm, não há necessidade de armadura de pele </br>";
        } else {
            $ASkin = 0.001*$bw*$h;

            echo "</br> ASpele = $ASkin cm²</br>";
        }


        echo "------------ARMADURA MÍNIMA------------\n\n";
        if ($fck<50) {
            $fctm=0.3*$fck**(2/3);
        } else {
            $fctm=2.12*log(1+0.11*$fck);
        }
        $fctksup=1.3*$fctm;
        $ag=($bf-$bw)*$hf+$bw*$h;
        $ycg=(($bf-$bw)*(0.5*$hf**2)+$bw+(0.5*$h**2))/$ag;
        $inertia=(($bf-$bw)*$hf**(3))/(12)+($bw*$h**(3))/(12)+($bf-$bw)*$hf*($ycg-0.5*$hf**(2))+$bw*$h*($ycg-0.5*$h)**(2);

        $wo=$inertia/($h-$x);
        $mdmin=0.8*$wo*$fctksup;
        $asmin = $mdmin/(($d-$d1)*$fyd);

        echo "</br> Asmin = $asmin cm²</br>";

    ?>
</div>
</body>


