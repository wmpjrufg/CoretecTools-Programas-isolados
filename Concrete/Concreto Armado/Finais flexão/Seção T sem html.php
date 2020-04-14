<!DOCTYPE html>
<link rel="stylesheet" href="_css/estilo.css"/>
<meta charset="UTF-8"/>
<title>Curso de PHP - CursoemVideo.com</title>
<body>
<div>
    <?php
        // Esforços
        echo "--------------ESFORÇOS NA SEÇÃO--------------\n\n";
        $mk = 6770; // kN.m
        echo "\n mk = $mk kN.m";
        $md = $mk * 140; // kN.cm
        echo "\nMd = $md kN.cm \n";

        //propriedades
        echo "----------PROPRIEDADES DO MATERIAL----------\n\n";
        $fck = 30; // MPa
        echo "\n fck = $fck MPa";
        $fcd = $fck * 0.1 / 1.4; // kN/cm²
        echo "\n fcd = $fcd kN/cm²";
        $aco = "CA50";
        echo "\n Aço $aco";


        $aco = "CA50";
        if ($aco == "CA50"){
            $fyk = 500; //MPa
        }
        elseif ($aco == "CA60"){
            $fyk = 600; //MPa
        }
        echo "\nfyk = $fyk MPa";
        $fyd = $fyk * 0.1 / 1.15; // kN/cm²
        echo "\nfyd = $fyd kN/cm²\n";

        //coeficientes alfac e lambda
        echo "----------COEFICIENTES ALFAC E LAMBDA----------\n\n";
        if ($fck<=50) {
            $alfac = 0.85;
            $lim23=0.259;
            $lim34=0.6283;

            echo "\n alfac = $alfac";
            $lambda = 0.8;
            echo "\n lambda = $lambda \n";
        }
        else{

            $alfac = .85*(1-($fck-50)*(1/200));
            echo "\n alfac = $alfac";
            $lambda = .8-($fck-50)*(1/400);
            echo "\n lambda = $lambda \n";
            $epsiloncu=0.0026+0.035*((90-$fck)/100)**4;
            $lim23= $epsiloncu/($epsiloncu+0.01);
            $lim34= $epsiloncu/($epsiloncu+0.00207);

        }
        echo "\n limite entre os dominios 2 e 3 = $lim23 \n";
        echo "limite entre os dominios 3 e 4 = $lim34 \n";

        // dados da seção
        echo "------------PROPRIEDADES DA SEÇÃO------------\n\n";
        $hf = 20; //cm
        echo "\n hf = $hf cm";
        $bw = 18; //cm
        echo "\n bw = $bw cm";
        $bf = 170; //cm
        echo "\n bf = $bf cm";
        $d = 175; //cm
        echo "\n d = $d cm";
        $d1 = 5; //cm
        echo "\n d1 = $d1 cm";
        $h = $d+ $d1; //cm
        echo "\n h = $h cm\n";

        //aleff
        $cob = 3; //cm
        $phiestribo = 6.3; //mm
        echo "------------VERIFICAÇÃO DA SEÇÃO T------------\n\n";

        //POSIÇÃO DA LN SUPONDO QUE PASSE NA MESA DA VIGA bw=bf
        $bwteste = $bf;
        echo "\n bw = bf = $bwteste cm";
        echo "\n md = $md kN.cm";
        echo "\n d = $d cm";
        echo "\n fcd = $fcd kN/cm²";
        $kmd = $md/($bwteste*$d**2*$fcd);
        echo "\n kmd = $kmd";
        $kx = (1-sqrt(1-2*$kmd/$alfac))/$lambda;
        echo "\n kx = $kx";
        $x = $kx*$d;
        echo "\n x = $x cm\n";
        $mdlim = .251*$bw*$d**2*$fcd;

        $ASpe= 2*pi()*.63**2*(1/4); //ver com o wand

        //ARMADURA
        if ($x<$hf) {
            $T = "FALSO";
            $M1 = $md;
            $M2=0;
            $AS2 = 0;
            $AS22 = 0;
            $AStotalcomp = $ASpe; //
            $mdlim = .251*$bwteste*$d**2*$fcd;
            if ($md<$mdlim){
                $armadura = "SIMPLES";
            }
            $KZ = 1-(1/2)*$lambda*$kx;
            $AS1 = $md/($KZ*$d*$fyd);
            if ($kx<$lim23) {
                $dominio=2;
            }
            elseif ($kx>=$lim23 && $kx<$lim34) {
                $dominio=3;
            }
            else{
                $dominio = 4;
            }
        }
        else {
            $T = "VERDADEIRO";
            $M1 = .85*$fcd*$hf*($bf-$bw)*($d-(1/2)*$hf); // parcela de momento resistido pelas abas
            $AS1 = $M1/(($d-(1/2)*$hf)*$fyd);// Área de aço das Abas
            $M2 = $md- $M1;// parcela de momento que traciona a alma
            if ($M2<$mdlim){
                $armadura = "SIMPLES";
                $AS22 = 0; // Área de aço comprimida em caso de armadura dupla
                $AStotalcomp = $ASpe;//se simples, apenas temos porta estribos
            }
            else {
                $armadura = "dupla";
                $M2comp = $M2 - $mdlim; //parcela de momento que comprime a alma
                $M2 = $mdlim;
                $AS22 = $M2comp/(($d-$d1)*$fyd);
                $AStotalcomp = $AS22;
            }
            $kmd = $M2/($bw*$d**2*$fcd);
            $kx2 = (1-sqrt(1-2*$kmd/$alfac))/$lambda;
            if ($kx2<.259) {
                $dominio=2;
            }
            elseif ($kx2>=0.259 && $kx2<0.6283) {
                $dominio=3;
            }
            else{
                $dominio = 4;
            }
            $KZ = 1-(1/2)*$lambda*$kx2;
            $AS2 = $M2/($KZ*$d*$fyd);// área de aço tracionada na alma
        }
        //ARMADURA DE AÇO LONGITUDINAL
        echo "------------ARMADURA DE AÇO LONGITUDINAL------------\n\n";

        echo "\n T = $T";
        echo "\n Dominio = $dominio";
        echo "\n armadura = $armadura\n";

        //CONTRIBUIÇÃO DA ALMA
        echo "------------CONTRIBUIÇÃO DA ALMA------------\n\n";

        echo "\n M2 = $M2 kN.cm";
        echo "\n Área de aço tracionada As2 = $AS2 cm²";
        echo "\n Área de aço comprimida As22 = $AS22 cm²\n";

        //CONTRIBUIÇÃO DA MESA

        echo "------------CONTRIBUIÇÃO DA MESA------------\n\n";

        echo "\n M1 = $M1 kN.cm";
        echo "\n As1 = $AS1 cm²\n";

        //ÁREA TOTAL DE AÇO TRACIONADA
        echo "------------ÁREA TOTAL DE AÇO CALCULADA TRACIONADA------------\n\n";

        $AScalc = $AS1 + $AS2;
        echo "\n AScalc = $AScalc cm²";

        echo "-------------DESBITOLAR ÁREA DE AÇO TRACIONADA------------\n\n";


        //Parte 9 - Verificações
        echo "---------------------------------------------\n\n";

        //Vetores com as bitolas para CA50
        $bit50 = array (5, 6.3, 8.0, 10.0, 12.5, 16.0, 20.0, 25.0, 32.0, 40.0); //mm
        $tam50 = count ($bit50);

        //valores auxiliares para os espaçamentos
        $dmax = 1.9; //cm (diâmetro máximo do agregado)
        $dauxh = 1.2 * $dmax; //cm
        $dauxv = 0.5 * $dmax; //cm

        //Espaço disponível para alocação das barras:
        $bdisp = $bw - 2 * ($cob + $phiestribo/10);
        echo "bdisp = $bdisp cm \n\n";

        echo "---------------------------------------------\n\n";
        echo "Bitolas para As1 = $AScalc cm²\n\n";
        echo "---------------------------------------------\n\n";

        for ($i = 0; $i < $tam50; $i++){

            $phi = $bit50 [$i]/10;
            $asphi = (pi() * pow ($phi, 2))/4;
            $n = ceil ($AScalc/$asphi);

            echo "$n Φ ", $phi*10, "\n";

            $phil = $phi + 0.04 * $phi;
            $ah = 0;
            $av = 0;

            //espaçamento horizontal
            if ($dauxh >= 2){
                $ah = $dauxh;
            }
            else{
                $ah = 2;
            }
            if ($ah <= $phil){
                $ah = $phil;
            }
            echo "Ah = $ah cm\n";

            //espaçamento vertical
            if ($dauxv >= 2){
                $av = $dauxv;
            }
            else{
                $av = 2;
            }
            if ($av <= $phil){
                $av = $phil;
            }
            echo "Av = $av cm\n";

            //Número máximo de barras por camada;
            $nmax = floor (($bdisp + $ah)/($phil + $ah));

            echo "Número máximo de barras por camada = $nmax\n";

            //Critério de número mínimo por camada (Quando for desenhar, utilizar o valor do nbar)
            if ($n == 1){
                $nbar = 2;
            }
            else{
                $nbar = $n;
            }

            //Número de camadas necessárias
            $ncam = ceil ($nbar/$nmax);

            echo "Número de camadas = $ncam\n";

            if ($nbar == 2 && $ncam <> 1){
                echo "Espaço insuficiente para alojar a armadura.\n\n\n";
            }
            else{
                //Centróide da Armadura
                $numerador = 0;
                $denominador = 0;
                $y1 = $cob + ($phiestribo/10) + ($phil/2);

                for ($j=1; $j <= $ncam; $j++){
                    if ($nbar >= $nmax){
                        $nj = $nmax;
                    }
                    else{
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

                if ($dreal >= $d && $ateste <= 0.1*$h){
                    echo "OK!\n\n\n";
                }
                else{
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
//        echo "\nbitola adotada = Φ$dbarra";
//        $dmaxagr=20;
//        echo "\nagredado adotado = Φ$dmaxagr mm";
//        $ah= max(20,$dbarra,1.2*$dmaxagr)/10;
//        echo "\nespaçamento horizontal = $ah cm";
//        $av= max(20,$dbarra,0.5*$dmaxagr)/10;
//        echo "\nespaçamento vertical = $av cm \n";

        //ÁREA TOTAL DE AÇO COMPRIMIDA
        echo "------------ÁREA TOTAL DE AÇO CALCULADA COMPRIMIDA------------\n\n";

        echo "\n AScomp = $AStotalcomp cm²";

        echo "-------------DESBITOLAR ÁREA DE AÇO COMPRIMIDA------------\n\n";

        //VERIFICAÇÕES

        echo "---------------------------------------------\n\n";
        echo "Bitolas para As2 = $AStotalcomp cm²\n\n";
        echo "---------------------------------------------\n\n";

        for ($i = 0; $i < $tam50; $i++){

            $phi = $bit50 [$i]/10;
            $asphi = (pi() * pow ($phi, 2))/4;
            $n = ceil ($AStotalcomp/$asphi);

            echo "$n Φ ", $phi*10, "\n";

            $phil = $phi + 0.04 * $phi;
            $ah = 0;
            $av = 0;

            //espaçamento horizontal
            if ($dauxh >= 2){
                $ah = $dauxh;
            }
            else{
                $ah = 2;
            }
            if ($ah <= $phil){
                $ah = $phil;
            }
            echo "Ah = $ah cm\n";

            //espaçamento vertical
            if ($dauxv >= 2){
                $av = $dauxv;
            }
            else{
                $av = 2;
            }
            if ($av <= $phil){
                $av = $phil;
            }
            echo "Av = $av cm\n";

            //Número máximo de barras por camada;
            $nmax = floor (($bdisp + $ah)/($phil + $ah));

            echo "Número máximo de barras por camada = $nmax\n";

            //Critério de número mínimo de barras por camada (Quando for desenhar, utilizar o valor do nbar)

            if ($n == 1){
                $nbar = 2;
            }
            else{
                $nbar = $n;
            }

            //Número de camadas necessárias
            $ncam = ceil ($nbar/$nmax);

            echo "Número de camadas = $ncam\n";

            if ($nbar == 2 && $ncam <> 1){
                echo "Espaço insuficiente para alojar a armadura.\n\n\n";
            }
            else{
                //Centróide da Armadura
                $numerador = 0;
                $denominador = 0;
                $y1 = $cob + ($phiestribo/10) + ($phil/2);

                for ($j=1; $j <= $ncam; $j++){
                    if ($nbar >= $nmax){
                        $nj = $nmax;
                    }
                    else{
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

                if ($dreal >= $d && $ateste <= 0.1*$h){
                    echo "OK!\n\n\n";
                }
                else{
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
//        echo "\nbitola adotada = Φ$dbarrac";
//        echo "\nagregado adotado = Φ$dmaxagr mm";
//        if ($ASpe>$AS22)
//            echo "\nporta estribo = 2Φ$dbarrac em $bw cm \n";
//        else{
//            $ahc= max(20,$dbarrac,1.2*$dmaxagr)/10;
//            echo "\nespaçamento horizontal = $ahc cm";
//            $avc= max(20 ,$dbarrac,0.5*$dmaxagr)/10;
//            echo "\nespaçamento vertical = $avc cm\n";
//        }

        //ÁRMADURA DE PELE
        echo "------------ARMADURA DE PELE------------\n\n";

        if ($h<60) {
            echo "Como a altura é menor que 60 cm, não há necessidade de armadura de pele \n";
        }
        else{
            $ASkin = 0.001*$bw*$h;

            echo "\n ASpele = $ASkin cm²\n";
        }


        echo "------------ARMADURA MÍNIMA------------\n\n";
        if ($fck<50){
            $fctm=0.3*$fck**(2/3);
        }
        else {
            $fctm=2.12*log(1+0.11*$fck);
        }
        $fctksup=1.3*$fctm;
        $ag=($bf-$bw)*$hf+$bw*$h;
        $ycg=(($bf-$bw)*(0.5*$hf**2)+$bw+(0.5*$h**2))/$ag;
        $inertia=(($bf-$bw)*$hf**(3))/(12)+($bw*$h**(3))/(12)+($bf-$bw)*$hf*($ycg-0.5*$hf**(2))+$bw*$h*($ycg-0.5*$h)**(2);

        $wo=$inertia/($h-$x);
        $mdmin=0.8*$wo*$fctksup;
        $asmin = $mdmin/(($d-$d1)*$fyd);

        echo "\n Asmin = $asmin cm²\n";

    ?>
</div>
</body>


