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

// Name:    Determinação da perda de tensão por escorregamento
// Version: PROTENSAO02_v00_perda_de_protensao_por_escorregamento_dos_cabos
// Notes:
// 
//
//
//
//
//
// ---------- Descrição do programa:
//
// O programa abaixo determina a perda de tensão devido ao escorregamento dos cabos, que ocorre devido a acomodação das cunhas na ancorragem.
//
// ----------

// ---------- Descrição das variáveis
//
// Escalares:
//
// L             - Comprimento da pista de protensão                 (mm)
// deltae        - Escoregamento / acomodação dado pelo fabricante   (mm)
// deltaLp       - Alongamento do aço                                (mm)
// Pi            - Carga aplicada a protensão durante a operação     (kN)
// Ap            - Área da seção transvesal do cabo                  (mm²)
// sigmapi       - Tensão na armadura na operação de protensão       (kN/mm²)
// Ep            - Módulo de elasticidade do aço                     (kN/mm²)
// deltaepsilonp - Redução na deformação da armadura                 (mm/mm)
// deltasigmap   - Redução na tensão da armadura                     (kN/mm²)
// deltap        - Carga perdida devido ao escorregamento dos cabos  (kN)
// P             - Carga após a perda                                (kN)
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
//
//=========================================================================%
// STEP 1: DECLARAÇÃO DOS PARÂMETROS DE ENTRADA
//=========================================================================%
//
//
// print setup
echo "-----------CORETEC TOOLS - CONCRETE-----------\n";
echo "PROJETO DE PEÇAS PROTENDIDAS - CÁLCULO DA PERDA DE TENSAO POR ESCORREGAMENTO\n";
echo "-----------------------------------------------\n\n";

// Step 1.1: Definição dos parâmetros de entrada

$L       = 121400;
$Pi      = 1039.4;
$Ap      = 722;
$Ep      = 202;
$deltae  = 4;   

// print setup
echo "-----------------------------------------------\n";
echo "PARÂMETROS DE ENTRADA:\n";
echo "-----------------------------------------------\n";
echo "Comprimento da pista de protensão = $L mm\n";
echo "Carga de protensão                = $Pi kN\n";
echo "Área da seção transvesal do cabo  = $Ap mm\n";
echo "Módulo de elasticidade do aço     = $Ep kN/mm²\n";
echo "Escorregamento                    = $deltae mm\n";
echo "-----------------------------------------------\n\n";

// Step 1.2: Determinação da tensão inicial 

$sigmapi = $Pi / $Ap;

// Step 1.3: Determinação do pré-alongamento do cabo 

$deltaLp = $L * ($sigmapi/$Ep);

// Step 1.4: Redução na deformação da armadura

$deltaepsilonp = $deltae / ($L + $deltaLp);

// Step 1.5: Redução na tensão da armadura 

$deltasigmap = $Ep * $deltaepsilonp;

// Step 1.6: Carga após a perda de tensão por escorregamento

$deltaP = ($deltasigmap * $Ap);

// Step 1.7: Carga após a perda de tensão por escorregamento

$P = $Pi - $deltaP;

// print setup
echo "-----------------------------------------------\n";
echo "PROCESSAMENTO DA PERDA DE CARGA Pi:\n";
echo "-----------------------------------------------\n";
echo "Perda de carga por escorregamento = $deltaP kN\n";
echo "Carga após a perda por escorregamento = $P kN\n";
echo "-----------------------------------------------\n\n";

?>