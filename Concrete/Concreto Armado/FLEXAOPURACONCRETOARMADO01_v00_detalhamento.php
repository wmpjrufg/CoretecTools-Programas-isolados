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

// Nome:    Informa os possíveis detalhamentos no dimensionamento de seções retangulares à flexão pura
// Versão:  FLEXAOPURACONCRETOARMADO01_v00_detalhamento
// Notas:
// 01-01-19 - A. L. G. Martins and R. V. Dutra finalizaram a versão beta
// 01-01-20 - Prof. W. M. Pereira Junior and L. E. C. Mota Leite introduziram as verificações de erros e avisos
//
//
//
//
//
// ----------

// ---------- Descrição do programa
//
// O programa abaixo verifica avisos no programa FLEXAOPURACONCRETOARMADO01_v00_principal
//
// ----------

// ---------- Variáveis e suas descrições
//
// Escalares
//
// dmax          - Diâmetro máximo do agregado graúdo                       (cm)
// tam50         - Tamanho do vetor de bitolas de CA50
// as            - Área de aço na região tracionada que se deseja detalhar  (cm2)
// i             - Contador do for para as bitolas
// phi           - Bitola da barra                                          (cm)
// asphi         - Área da seção da barra phi                               (cm2)
// numbarrasnec  - Número total de barras necessárias
// nbar          - Número total de barras necessárias
// phil          - Bitola da barra contabilizando a mossa                   (cm)
// ah            - Altura horizontal                                        (cm)
// av            - Altura vertical                                          (cm)
// bdisp         - Largura máxima disponível                                (cm)
// nmaxdebarporcam - Número máximo de barras por camad
// nj              - Contabiliza sempre nmaxdebarporcam para fazer o contador corretamente
// y1              - Centroíde da primeira camada de aço
// j               - Contador do for do número de camadas
// numerador       - Somatório yi.Ai para fazer centroíde das barras
// denominador     - Somatório Ai para fazer centroíde das barras
// ycgarmadura     - Centroíde total do conjunto de armaduras
// dreal           - Altura útil real das armaduras
// 
//
// Vetores:
//
// Bit50           - Vetor que contém as bitolas de CA50 e CA60            (mm)   
//
//
// Matrizes:
//
//
//
//
// ----------

// ---------- Chamada de funções
//
include_once('FLEXAOPURACONCRETOARMADO01_v00_avisos.php');
//
// ----------

function FLEXAOPURACONCRETOARMADO01_v00_detalhamento($dmax,$as,$h,$d,$bw,$cob,$phiestribo)
{

//
//
//=========================================================================%
// STEP 1: CRIAÇÃO DOS POSSÍVEIS DETALHAMENTOS EM AÇO CA-50
//=========================================================================%
//
//
// Step 1.1: Vetores de detalhamento com as bitolas para CA50
$Bit50 = array (5, 6.3, 8.0, 10.0, 12.5, 16.0, 20.0, 25.0, 32.0, 40.0); 
$tam50 = count ($Bit50);
//
//
//=========================================================================%
// STEP 2: DETALHAMENTO DA ÁREA DE AÇO as1
//=========================================================================%
//
//
// print setup
echo "\n";
echo "-----------------------------------------------\n";
echo "DETALHAMENTO PARA As = $as cm²\n";
echo "-----------------------------------------------\n";
echo "\n";

for ($i = 0; $i < $tam50; $i++){
  
  // Step 2.1: Determinação da bitola em cm e sua área de seção
  $phi          = $Bit50 [$i]/10;
  $asphi        = (pi() * pow ($phi, 2))/4;
  $numbarrasnec = ceil ($as/$asphi);
  
  // print setup
  echo "Detalhamento Φ $Bit50[$i] \n";
  echo "Total de barras   = $numbarrasnec Φ ", $phi*10, "\n";
  echo "AsΦ               = ", $asphi, " cm2\n";
  
  // Step 2.2: Acréscimo da mossa para detalhamento
  $phil = $phi + 0.04 * $phi;
  echo "Φ+mossa           = ", $phil*10, " mm\n";

  // Step 2.3: Inicializando altura vertical e horizontal
  $ah = 0;
  $av = 0;
  
  // Step 2.4: Condições para o espaçamento horizontal
  if (1.2 * $dmax >= 2){
    $ah = 1.2 * $dmax;
  }
  else{
    $ah = 2;
  }
  if ($ah <= $phil){
    $ah = $phil;
  }
 
  // Step 2.5: Condições para o espaçamento vertical
  if (0.5 * $dmax >= 2){
    $av = 0.5 * $dmax;
  }
  else{
    $av = 2;
  }
  if ($av <= $phil){
    $av = $phil;
  }

  echo "Altura Horizontal (ah) = ", $ah, " cm\n";
  echo "Altura Vertical (av)   = ", $av, " cm\n";

  // Step 2.6: Número máximo de barras por camada
  $bdisp           = $bw - 2 * ($cob + $phiestribo/10);
  $nmaxdebarporcam = floor (($bdisp + $ah)/($phil + $ah));

  echo "Largura disp           = ", $bdisp, " cm\n";
  echo "Nbarras por camada     = ", $nmaxdebarporcam, "\n";

  // Step 2.7: Critério de número mínimo por camada (Quando for desenhar as bitolas, utilizar o valor do nbar)
  if ($numbarrasnec == 1){
    $nbar = 2;
  }
  else{
    $nbar = $numbarrasnec;
  }

  // Step 2.8: Número de camadas necessárias (Quando for desenhar as bitolas, utilizar o valor do ncam)
  $ncam = ceil ($nbar/$nmaxdebarporcam);

  // print setup
  echo "Total de camadas       = $ncam\n";
  
  // Step 2.9: Alojamento das barras
  if ($nbar == 2 && $ncam <> 1){
    echo "Espaço insuficiente para alojar a armadura.\n\n"; //Mensagem para o usuário caso a bitola selecionada do droplist não atenda a verificação
  
  }
  
  else {
    
    // Step 2.9.1: Verificação do centróide da armadura
    $numerador   = 0;
    $denominador = 0;

    // Step 2.9.2: Centróide da armadura da primeira camada
    $y1 = $cob + ($phiestribo/10) + ($phi/2);
  
    // Step 2.9.3: Posicionanmento do centróide da armadura das camadas subsequentes
    for ($j=1; $j <= $nmaxdebarporcam; $j++){
      
      if ($nbar >= $nmaxdebarporcam){

        $nj = $nmaxdebarporcam;
     }

      else{

        $nj = $nbar;
     
     }
    
     $nbar        = $nbar - $nj;
     $ycam        = $y1 + ($j-1) * ($av + $phi);
     $numerador   = $numerador + ($asphi * $nj * $ycam);
     $denominador = $denominador + ($asphi * $nj);
    }

	// Step 2.9.4: Determinação do ycg da armadura e do dreal
    $ycgarmadura = $numerador / $denominador;
    $dreal       = $h - $ycgarmadura;
    $ateste = $ycgarmadura - $y1;
    $testeh = (10/100)*$h;

    // print setup
    echo "Ycg_armadura = $ycgarmadura cm \n";
    echo "dreal        = $dreal cm \n";
    echo "a            = $ateste cm \n";
    echo "10%.h        = $testeh cm \n";
        
    if ($dreal >= $d && $ateste <= 0.1*$h){
      echo "OK!: deral > d \n\n";
    }

    else{

      if ($dreal < $d) {
      	$aviso=3;
        FLEXAOPURACONCRETOARMADO01_v00_avisos($aviso); 
      }

      if ($ateste > 0.1*$h) {
        $aviso=4;
        FLEXAOPURACONCRETOARMADO01_v00_avisos($aviso); 
      }
      
      echo "\n";
    }
  }
}

}

?>