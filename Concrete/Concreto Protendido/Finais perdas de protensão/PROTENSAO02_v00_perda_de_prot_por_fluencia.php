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
//Alpha    - Coeficiente do tipo de cimento (?)
//U       - Umidade relativa do ar (%)
//Gama    - Coefifiente da função da umidade relativa (?)
//Hfict    - Altura fictícia do concreto (cm)
//Betaf   - Coeficiente relativo a fluência irreversível (?)
//t       - tempo (dias)
//t0      - 
//Fia     - ??????????
//Beta1t             - Coeficiente relacionado a idade e tipo cimento 
//Beta128             - Coeficiente relacionado a idade e tipo cimento
//Fif      - ???????????
//Fi1c
//Fi2c
//Fid    -
//Fitt
//Betad
//
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
// STEP 1: CÁLCULO DA PERDA POR ENCURTAMENTO ELÁSTICO IMEDIATO
//=========================================================================%
//
//print setup
echo "Coretec Tools";
//
// Step 1.1: Parâmetros de entrada do algoritmo
$Alpha    =2;
$t        =8;
$t0       =3;
$Fid      =0.4;
//
// print setup
xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
//
//
//Step 1.2: Cálculo do gama
$Gama      = 1+exp(-7.8+0.1*$U);
//
//Step 1.3: Cálculo da Hfict
$Hfict          = $Gama*2*$Ac/$U;
if ($Hfict<0.05) {
	$Hfict = 0.05;
} else ($Hfict>1.6 {
	$Hfict = 1.6;
}
//Step 1.4 : Determinação do Betaf
$h2 = $Hfict*$Hfict;
$h3 = $h2*$Hfict;
$A  = 42*$h3-350*$h2+588*Hfict+113;
$B  = 768*$h3-3060*$h2+3234*$Hfict-23;
$C  = -200*$h3+13*$h2+1090*$Hfict+183;
$D  = 7579*$h3-31916*$h2+35343*$Hfict+1931;
$Betaft = (1*$t*$t+$A*$t+$B)/($t*$t+$D);
$Betaft0 = (1*$t0*$t0+$A*$t0+$B)/($t0*$t0+$D);
$Betaf = $Betaft-$Betaft0
//
//Step 3.2: Determinação do Beta1t
$Beta1t    = exp($S*(1-sqrt(28/$t)));
//
//Step 3.2: Determinação do Beta128
$t28    = 28;
$Beta128    = exp($S*(1-sqrt(28/$t28)));
//
//Step 1.4 : Cálculo do Fia:
if ($fck<45) {
	$Fia = 0.8*(1-($Beta1t/$Beta128));
} else  {
	$Fia = 1.4*(1-($Beta1t/$Beta128));
} 
//
//Step 1.4 : Cálculo do Fi1c:
if ($U<90) {
	$Fi1c = 4.450.035*$U;
} else  {
	$Fi1c = 0.8;
} 
//
//Step 1.4 : Cálculo do Fi2c:
$Fi2c  = (0.42+$Hfict)/(0.2+$Hfict);
//
//Step 1.4 : Cálculo do Fif:
$Fif  = $Fi1c*$Fi2c;
//
//Step 1.4 : Cálculo do Betad:
$Betad = ((t-t0)+20)/((t-t0)+70);
//
//Step 1.7 : Cálculo do coeficiente de fluência total:
$Fitt = $Fia+$Fif*$Betaf+$Fid*$Betad;
//
// print setup
xxxxxxxxxxxx;
//
?>