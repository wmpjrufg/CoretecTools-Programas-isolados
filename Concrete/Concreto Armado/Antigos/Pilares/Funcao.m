function f=Funcao(es,eu,beta,e0,ami,qsi,akapa,alamb,ni,fyd,ani,nl)
global soma1 soma2 rc bc
% IMPLICIT REAL*8(A-H,O-Z)
%Vari�veis p�blicas
% COMMON /A1/ ni(20),beta(20)
% COMMON /A2/ n,nl
% COMMON /A3/ delta,ani,ami,fyd,fcd,es
% COMMON /A4/ alfac,alamb,e0,eu,akapa
%
%Calcula o valor da fun��o f(qsi) dada na equa��o (2.5.11)
%do Volume 3 de Curso de Concreto Armado
%
%qsi=x/h � a profundidade relativa da linha neutra
%rc � a resultante de compress�o do concreto adimensional dada na equa��o (2.4.4)
%bc � a posi��o da resultante adimensional dada na equa��o (2.4.5)
%soma1 � o somat�rio contido no denominador da equa��o (2.5.9)
%soma2 � o somatat�rio contido no denominador da equa��o (2.5.10)
%f � o resultado da equa��o (2.5.11)
%
%O �nico par�metro de entrada � qsi, pois as demais vari�veis s�o p�blicas.
%Os par�metros rc,bc,soma1,soma2,f s�o calculados nessa sub-rotina
%
%
%Constantes para o c�lculo das deforma��es das camadas da armadura
ql=eu*beta(1)/(eu+10);
if(qsi<=ql)
    %A linha neutra est� no dom�nio 2
    c=0.01/(beta(1)-qsi);
end
if(qsi<=1)
    %A linha neutra est� nos dom�nios 3,4 e 4a
    c=eu/(1000*qsi);
else
    %A linha neutra est� no dom�nio 5
    c=(e0/1000)/(qsi-akapa);
end
%
%Resultante de compress�o no concreto
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
%Fun��o f(qsi)
f=(ami-0.5*ani+rc*bc)*soma1+(ani-rc)*soma2;
end