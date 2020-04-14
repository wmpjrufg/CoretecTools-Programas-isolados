function tsl=Tensao(es,esl,fyd)
% IMPLICIT REAL*8(A-H,O-Z)
%Variáveis públicas
% COMMON /A3/ delta,ani,ami,fyd,fcd,es
%
%Calcula a tensão no aço
%es = módulo de elasticidade do aço em kN/cm2
%esl = deformação de entrada
%fyd = tensão de escoamento de cálculo em kN/cm2
%tsl = tensão de saída em kN/cm2
%
%Trabalhando com deformação positiva
ess=abs(esl);
eyd=fyd/es;
if(ess<eyd)
    tsl=es*ess;
else
    tsl=fyd;
end
%Trocando o sinal se necessário
if(esl<0)
    tsl=-tsl;
end
end