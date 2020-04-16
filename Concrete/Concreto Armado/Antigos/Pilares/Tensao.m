function tsl=Tensao(es,esl,fyd)
% IMPLICIT REAL*8(A-H,O-Z)
%Vari�veis p�blicas
% COMMON /A3/ delta,ani,ami,fyd,fcd,es
%
%Calcula a tens�o no a�o
%es = m�dulo de elasticidade do a�o em kN/cm2
%esl = deforma��o de entrada
%fyd = tens�o de escoamento de c�lculo em kN/cm2
%tsl = tens�o de sa�da em kN/cm2
%
%Trabalhando com deforma��o positiva
ess=abs(esl);
eyd=fyd/es;
if(ess<eyd)
    tsl=es*ess;
else
    tsl=fyd;
end
%Trocando o sinal se necess�rio
if(esl<0)
    tsl=-tsl;
end
end