function f=Funcao(es,eu,beta,e0,ami,qsi,akapa,alamb,ni,fyd,ani,nl)
global soma1 soma2 rc bc
% IMPLICIT REAL*8(A-H,O-Z)
%Variáveis públicas
% COMMON /A1/ ni(20),beta(20)
% COMMON /A2/ n,nl
% COMMON /A3/ delta,ani,ami,fyd,fcd,es
% COMMON /A4/ alfac,alamb,e0,eu,akapa
%
%Calcula o valor da função f(qsi) dada na equação (2.5.11)
%do Volume 3 de Curso de Concreto Armado
%
%qsi=x/h é a profundidade relativa da linha neutra
%rc é a resultante de compressão do concreto adimensional dada na equação (2.4.4)
%bc é a posição da resultante adimensional dada na equação (2.4.5)
%soma1 é o somatório contido no denominador da equação (2.5.9)
%soma2 é o somatatório contido no denominador da equação (2.5.10)
%f é o resultado da equação (2.5.11)
%
%O único parâmetro de entrada é qsi, pois as demais variáveis são públicas.
%Os parâmetros rc,bc,soma1,soma2,f são calculados nessa sub-rotina
%
%
%Constantes para o cálculo das deformações das camadas da armadura
ql=eu*beta(1)/(eu+10);
if(qsi<=ql)
    %A linha neutra está no domínio 2
    c=0.01/(beta(1)-qsi);
end
if(qsi<=1)
    %A linha neutra está nos domínios 3,4 e 4a
    c=eu/(1000*qsi);
else
    %A linha neutra está no domínio 5
    c=(e0/1000)/(qsi-akapa);
end
%
%Resultante de compressão no concreto
if(qsi<1/alamb)
    rc=alamb*qsi;
    bc=0.5*alamb*qsi;
else
    rc=1;
    bc=0.5;
end
soma1=0;
soma2=0;
for i=1:nl
    esi=c*(qsi-beta(i));
    soma1=soma1+ni(i)*Tensao(es,esi,fyd);
    soma2=soma2+ni(i)*beta(i)*Tensao(es,esi,fyd);
end
%Função f(qsi)
f=(ami-0.5*ani+rc*bc)*soma1+(ani-rc)*soma2;
end