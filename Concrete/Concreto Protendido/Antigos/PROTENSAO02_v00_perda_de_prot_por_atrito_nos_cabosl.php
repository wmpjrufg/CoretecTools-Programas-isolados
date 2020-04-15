<?php

// ######################################################################
// UNIVERSIDADE FEDERAL DE CATALÃO (UFCat)
// DEPARTAEMNTO DE ENGENHARIA CIVIL
// Autores
// Carlos Magno Silva Carneiro
// Walfrido Carvalho do Valle
// Colaboradores
// Wanderlei Malaquias Pereira Junior
// ######################################################################

// Name:    Determinação da perda de tensão por atrito na protensao
// Version: PROTENSAO02_v00_perda_de_protensao_por_atrito_nos_cabos
// Notes:
// 
//
//
//
//
//
// ---------- Descrição do programa:
//
// O programa abaixo determina a perda de tensão devido ao atrito nos cabos
//
// ----------

// ---------- Descrição das variáveis
//
// Escalares:
//
// Pi          - Força máxima admitida do aço                                                       (kN)
// X           - Comprimento do cabo medido a partir da ancoragem                                   (m)
// alfa        - Ângulo de desvio entre a ancoragem e o ponto da abscissa x.                        (radianos)
// fator micro - Ângulo de atrito aparente entre o cabo e a bainha.                                 (1/radianos)
// fator k     - Coeficiente de perda por metro provocadas por curvaturas não intencionais do cabo. (1/m)
//
//
// Vetores:
//
//
//
//
// Matrizes:
//
//
//
//
// ----------
//
// ---------- Chamada de funções
//
//
//=========================================================================%
// STEP 1: DECLARAÇÃO DOS PARÂMETROS DE ENTRADA
//=========================================================================%
//
//
// print setup
echo "-----------CORETEC TOOLS - CONCRETE-----------\n";
echo "PROJETO DE PEÇAS PROTENDIDAS - CÁLCULO DA PERDA DE TENSAO POR ATRITO\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Definição dos parâmetros de entrada
$Pi           = 1000;   
$X            = 10;
$fatoralfa    = 1;
$fatormicro   = 1;   
$fatork       = 1;

// Step 1.2: Definição do fatormicro de acordo com o tipo de superficie de atrito


// Step 1.3: Determinação da força no cabo considerando a perda por atrito  


P = Pi * exp(^-((fatormicro*fatoralfa)+(fatork*X)))





