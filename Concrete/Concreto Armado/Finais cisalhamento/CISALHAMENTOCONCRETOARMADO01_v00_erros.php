<?php
// ######################################################################
// UNIVERSIDADE FEDERAL DE CATALÃO (UFCat)
// DEPARTAMENTO DE ENGENHARIA CIVIL & DEPARTAMENTO DE CIÊNCIAS DA COMPUTAÇÃO
// Autores
// Aleff Lucas Gonçalves Martins
// Roberto Viegas Dutra
// Colaboradores
// Luiz Eduardo Costa Mota Leite
// Newton Peixoto
// Pedro Henrique dos Santos Marques Vieira
// Luanna Lopes Lobato
// Wanderlei Malaquias Pereira Junior
// ######################################################################

// Nome:    Informa os erros identificados no programa de dimensionamento de seções retangulares submetidas à flexão pura
// Versão:  FLEXAOPURACONCRETOARMADO01_v00_erros
// Notas:
// 01-01-20 - Prof. W. M. Pereira Junior and L. E. C. Mota Leite introduziram as verificações de erros e avisos
//
//
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo verifica erros no programa FLEXAOPURACONCRETOARMADO01_v00_principal
//
// ----------

// ---------- Variáveis e suas descrições
//
// Escalares
//
// erro  - Código do erro no programa FLEXAOPURACONCRETOARMADO01_v00_principal
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


function CISALHAMENTOCONCRETOARMADO01_v00_erros($erro)
{

//
//
//=========================================================================%
// STEP 1: RETURN EM FUNÇÃO DOS ERROS DA ROTINA PRINCIPAL
//=========================================================================%
//
//
// Step 1.1: Verificação de erros

if       ($erro == 1) {
    echo "O fck deve ser inferior a 90 MPa\n";
} elseif ($erro == 2) {
    echo "O cobrimento (cob) recomendado deve ser inferior a 5.5 cm conforme Tabela 7.2 NBR 6118 \n";
} elseif ($erro == 3) {
    echo "Aço com fyk > 600 não são normatizados\n";
} elseif ($erro == 4) {
    echo "Não é permitida entrada de dados do tipo <=0 \n";
} elseif ($erro == 5) {
    echo "Verifique o valor do fck introduzido pos fck indicado maior que fyk\n";
} elseif ($erro == 6) {
    echo "Valor de deverá respeitar a relação d < h\n";
} elseif ($erro == 7) {
    echo "Valor do DMC (Diâmetro Máximo Característico) do agregado está maior que 10 cm\n";
} elseif ($erro == 8) {
    echo "No modelo I a inclinação da biela de compressão deve ser 45 graus obrigatoriamente\n";
} elseif ($erro == 9) {
    echo "Nos modelo I e II a inclinação da armadura deve estar entre  45 e 90 graus\n";
} elseif ($erro == 10) {
    echo "No modelo II a inclinação da biela de compressão deve estar entre  30 e 45 graus\n";
}

return;

}

?>