<?php
// ######################################################################
// UNIVERSIDADE FEDERAL DE CATALÃO (UFCAT)
// DEPARTAMENTO DE ENGENHARIA CIVIL
// Autores
// Carlos Magno Silva Carneiro
// Walfrido Carvalho do Valle
// Wanderlei Malaquias Pereira Junior
// Walter Albergaria
// ######################################################################

// ----------------------------
// Nome:    coretectoolsFreyssinetPerdaProtensaoAtritov0001
// Versão: 	versão 0001
// Notas:
// 15-04-2020 	- C. M. S. Carneiro and W. C. do Valle finalizaram a versão beta
// 18-08-2020 	- W. M. Pereira Junior alterações de estilo 
//
//
//
// ----------------------------


// ---------- Descrição do programa:
//
// O programa abaidistancia_xo determina a perda de tensão devido ao atrito nos cabos
//
// ----------

// ---------- Descrição das variáveis
//
//
// carga_prot_in 			- Força de Protensão inicial também chamada de carga Pi (kN)
// distancia_distancia_x 	- Comprimento do cabo medido a partir da ancoragem até a distância que se deseja verificar a perda de protensão (m)
// alfa        				- Ângulo de desvio entre a ancoragem e o ponto da abscissa distancia_x (radianos)
// fat_micro  				- Ângulo de atrito aparente entre o cabo e a bainha (1/radianos)
// fat_k      				- Coeficiente de perda por metro provocado por curvaturas não intencionais do cabo (1/m)
//
//
// ---------- Chamada de funções
//
//
//=========================================================================%
// ETAPA 1: DECLARAÇÃO DOS PARÂMETROS DE ENTRADA
//=========================================================================%
//
//
// impressão
echo "-----------CORETEC TOOLS - FREYSSINET-----------\n";
echo "		CÁLCULO DA PERDA DE TENSAO POR ATRITO\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Definição dos parâmetros de entrada que vêm do Front End
$carga_prot_inicial = 1000;
$distancia_distancia_x = 10;
$fat_alfa = 0.17;
$fat_micro = "entre barras ou fios com mossas ou saliência e bainha metálica";

// Step 1.2: Determinação do fator micro - coeficiente de atrito
if ($fat_micro == "entre cabo e concreto (sem bainha)") {
    echo "0.50";
    $fat_micro = 0.50;
} elseif ($fat_micro == "entre barras ou fios com mossas ou saliência e bainha metálica") {
    echo "0.30";
    $fat_micro = 0.30;
} elseif ($fat_micro == "entre fios lisos ou cordoalhas e bainha metálica") {
    echo "0.20";
    $fat_micro = 0.20;
} elseif ($fat_micro == "entre fios lisos ou cordoalhas e bainha metálica lubrificada") {
    echo "0.10";
    $fat_micro = 0.10;
} elseif ($fat_micro == "entre cordoalha e bainha de poliprocarga_prot_inicialleno lubrificada"){
    echo "0.05";
    $fat_micro = 0.05;
}

// Step 1.3: Determinação do fator k
$fat_k = 0.01 * $fat_micro;

// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Carga de protensão    = $carga_prot_inicial kN\n";
echo "Distância             = $distancia_x m\n";
echo "Alpha                 = $fat_alfa radiano\n";
echo "Coeficiente de atrito = $fat_micro 1/radiano\n";
echo "-----------------------------------------------\n\n";


// Step 1.3: Determinação da força no cabo considerando a perda por atrito
$edistancia_xpoenteparte1 = $fat_micro*$fat_alfa;
$edistancia_xpoenteparte2 = $fat_k*$distancia_x;
$edistancia_xpoentetotal  = -($edistancia_xpoenteparte1+$edistancia_xpoenteparte2);
$Perdacarga     = $carga_prot_inicial-($carga_prot_inicial*edistancia_xp($edistancia_xpoentetotal));
$Perdaperc      = ($Perdacarga/$carga_prot_inicial)*100;
$carga_prot_inicialaposperda    = $carga_prot_inicial*edistancia_xp($edistancia_xpoentetotal);

// print setup
echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DA PERDA DE CARGA carga_prot_inicial:\n";
echo "-----------------------------------------------\n";
echo "micro distancia_x alpha      = $edistancia_xpoenteparte1\n";
echo "k distancia_x l              = $edistancia_xpoenteparte2\n";
echo "edistancia_xpoente total     = $edistancia_xpoentetotal \n";
echo "Total da perda     = $Perdacarga kN\n";
echo "Total percentual   = $Perdaperc %\n";
echo "Carga após a perda = $carga_prot_inicialaposperda kN\n";
echo "-----------------------------------------------\n\n";

?>