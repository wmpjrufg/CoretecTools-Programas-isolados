<?php
// ######################################################################
// UNIVERSIDADE FEDERAL DE CATALÃO (UFCat)
// DEPARTAMENTO DE ENGENHARIA CIVIL & DEPARTAMENTO DE CIÊNCIAS DA COMPUTAÇÃO
// Autores
// Carlos Magno Silva Carneiro
// Walfrido Carvalho do Valle
// Colaboradores
// Luiz Eduardo Costa Mota Leite
// Wanderlei Malaquias Pereira Junior
// ######################################################################
//
//
// Nome:    Determinação da resistência a tração do concreto
// Versão:  CONCRETO02_v00_perda_de_prot_por_fluencia
// Notas:
// 15-04-20 - C. M. S. Carneiro and W. C. do Valle finalizaram a versão beta
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo determina a perda de protensão por fluência.
//
// ----------

// ---------- Variáveis e suas descrições
//
//
//Escalares:
//fck           - Resistência característica à compressão do concreto (MPa)
//Alpha                                 - Coeficiente do tipo de cimento ()
//U                                            - Umidade relativa do ar (%)
//Gama                          - Coefifiente da função da umidade relativa
//Hfict                                  - Altura fictícia do concreto (cm)
//Betaf                      - Coeficiente relativo a fluência irreversível
//t                                                          - tempo (dias)
//t0                                                         - tempo (dias)
//Fia                                      - Coeficiente de fluência rápida
//Beta1t                   - Coeficiente relacionado a idade e tipo cimento 
//Beta128                  - Coeficiente relacionado a idade e tipo cimento
//Fif                         - Coeficiente de deformação lenta ireversível
//Fi1c - Coeficiente dependente da umidade ambiente e consistência concreto
//Fi2c               - Coeficiente dependente da espessura fictícia da peça
//Fid                          - Coeficiente de deformação lenta reversível 
//Fitt                                      - Coeficiente de fluência final
//Betad                - Coeficiente fluência reversível em função do tempo
//h2                          - Coeficiente da fórmula Betaf
//h3                          - Coeficiente da fórmula Betaf
//S    - Coeficiente para o tipo de cimento usado no concreto
//Ac    - Área da seção de concreto (cm2)
//
//Vetores:
//
//
//
//
//Matrizes:
//
//
//=========================================================================%
// STEP 1: CÁLCULO DA PERDA POR FLUÊNCIA
//=========================================================================%
//
//print setup
echo "Coretec Tools";
//
// Step 1.1: Parâmetros de entrada do algoritmo
$t0         =59.733;
$t          =59.733;
$U         =60;
$S          =2;
$t28      = 6400;
$Ac      =2560;
$fck     = 30;
//
// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Período inicial                      = $t0 dias\n";
echo "Período final                         = $t dias\n";
echo "Umidade relativa do ambiente             = $U %\n";
echo "Coeficiente relacionado ao tipo cimento   = $S \n";
echo "Área da seção de concreto              =$Ac cm2\n";
echo "Fck do concreto                       =$fck MPa\n";
//
//
//Step 1.1: Cálculo do gama
$Gama      = 1+exp(-7.8+0.1*$U);
//
//Step 1.2: Cálculo da Hfict
$Hfict          = $Gama*2*$Ac/$U;
if ($Hfict<0.05) {
	$Hfict = 0.05;
} elseif ($Hfict>1.6) {
	$Hfict = 1.6;
} else { 
  $Hfict = $Gama*2*$Ac/$U;
}
//
//Step 1.3: Determinação do Betaf
$h2 = $Hfict*$Hfict;
$h3 = $h2*$Hfict;
$A  = 42*$h3-350*$h2+588*$Hfict+113;
$B  = 768*$h3-3060*$h2+3234*$Hfict-23;
$C  = -200*$h3+13*$h2+1090*$Hfict+183;
$D  = 7579*$h3-31916*$h2+35343*$Hfict+1931;
$Betaft = (1*$t*$t+$A*$t+$B)/($t*$t+$D);
$Betaft0 = (1*$t0*$t0+$A*$t0+$B)/($t0*$t0+$D);
$Betaf = $Betaft-$Betaft0;
//
//Step 1.4: Determinação do Beta1t
$Beta1t    = exp($S*(1-sqrt(28/$t)));
//
//Step 1.5: Determinação do Beta128
$Beta128    = exp($S*(1-sqrt(28/$t28)));
//
//Step 1.6: Cálculo do Fia:
if ($fck<45) {
	$Fia = 0.8*(1-($Beta1t/$Beta128));
} else  {
	$Fia = 1.4*(1-($Beta1t/$Beta128));
} 
//
//Step 1.7: Cálculo do Fi1c:
if ($U<90) {
	$Fi1c = 4.45-0.035*$U;
} else  {
	$Fi1c = 0.8;
} 
//
//Step 1.8: Cálculo do Fi2c:
$Fi2c  = (0.42+$Hfict)/(0.2+$Hfict);
//
//Step 1.9: Cálculo do Fif:
$Fif  = $Fi1c*$Fi2c;
//
//Step 1.10: Cálculo do Betad:
$Betad = (($t-$t0)+20)/(($t-$t0)+70);
//
//Step 1.11: Cálculo do coeficiente de fluência total:
$Fid  = 0.4;
$Fitt = $Fia+$Fif*$Betaf+$Fid*$Betad;
//
// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE SAÍDA:\n";
echo "-----------------------------------------------\n";
echo "Coeficiente Fia  = $Fia \n";
echo "Coeficiente de fluência final  = $Fitt \n";
//
?>