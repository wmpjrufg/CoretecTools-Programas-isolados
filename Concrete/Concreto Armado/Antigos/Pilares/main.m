function [fyk,fck,h,b,ank,le,M1dAx,M1dBx,M1dAy,M1dBy]=main
clc
clear all
close
global soma1 soma2 rc bc Mdx Mdy
%%%%%%%%%%%%%%%%%%
M1dAx=7000;
M1dBx=7000;
M1dAy=0;
M1dBy=0;
le=280;
%%%%%%%%%%%%%%%%%%

%Dimensionamento � Flexo-Compress�o Normal
%Se��es retangulares com v�rias camadas de armadura
%O programa est� limitado a 20 camadas
%
%PROGRAMA PRINCIPAL
%Resist�ncia caracter�stica � compress�o do concreto em MPa
fck=20;
%
%Tens�o de escoamento caracter�stica do a�o em MPa
fyk=500;
%
%M�dulo de elasticidade do a�o em GPa
es=210;
%
%Coeficientes parciais de seguran�a:
%para o concreto
gamac=1.4;
%para o a�o
gamas=1.15;
%para os esfor�os solicitantes
gamaf=1.4;
%Resist�ncias de c�lculo
fcd=fck/gamac;
fyd=fyk/gamas;
%% Dados da se��o transvesral:
%Largura em cm
b=40;
%Altura em cm
h=20;
%Dist�ncia d%
dl=max(0.2*h);
%
%% N�mero de camadas de armadura:
%A se��o deve ter, no m�nimo, duas camadas de armadura
%nl>=2
nl=2;
%
%N�mero de barras das camadas de armadura:
%As camadas s�o numeradas de baixo para cima
%Cada camada deve ter, no m�nimo, duas barras
% ni=[2 2];
ncamadas=2;
for z=1:nl
    ni(z)=ncamadas;
end
%Esfor�os solicitantes de servi�o:
%Entrar com valores positivos
%Esfor�o normal (em kN)
ank=500;
% %Momento fletor (em kNm)
% amk=143.5/1.4;
%FIM DA ENTRADA DE DADOS
p_entrada(fcd,h,b,ank,le,M1dAx,M1dBx,M1dAy,M1dBy);
amk=max(Mdx/140,Mdy/140)
%% IN�CIO DOS C�LCULOS
%
%Par�metros do diagrama retangular
if(fck<=50)
    alamb=0.8;
    alfac=0.85;
    eu=3.5;
    e0=2;
else
    alamb=0.8-(fck-50)/400;
    alfac=0.85*(1-(fck-50)/200);
    eu=2.6+35*((90-fck)/100)^4;
    e0=2+0.085*((fck-50)^0.53);
end
%Par�metro kapa que define o ponto com deforma��o igual a eo no dom�nio 5
akapa=1-e0/eu;
%
%Convers�o de unidades: transformando para kN e cm
fck=fck/10;
fyk=fyk/10;
fcd=fcd/10;
fyd=fyd/10;
es=100*es;
amk=100*amk;
%
%Resist�ncias de c�lculo
tcd=alfac*fcd;
%
%Esfor�os solicitantes de c�lculo
aand=gamaf*ank;
amd=gamaf*amk;
%
%C�lculo do n�mero total de barras na se��o
n=0;
for i=1:nl
    n=n+ni(i);
end
%
%Par�metro geom�trico
delta=dl/h;
%
%�rea da se��o de concreto
ac=b*h;
%
%Esforcos reduzidos
ani=aand/(ac*tcd);
ami=amd/(ac*h*tcd);
%
%Caso particular de compress�o centrada
%
if(ami==0)
    esi=e0/1000;
    w=(ani-1)*fyd/Tensao(es,esi,fyd);
    if(w<0)
        w=0;
    end
    %C�lculo da �rea de armadura total
    disp('armadura total')
    aas=w*ac*tcd/fyd
    return
end
%
%Flexo-compress�o normal
%
%Montagem do vetor beta
%Ver equa��o (2.2.5) do Volume 3 de Curso de Concreto Armado
%
for i=1:nl
    beta(i)=delta+(nl-i)*(1-2*delta)/(nl-1);
end
%Processo iterativo da bissecante
%
%Determina��o do intervalo solu��o
%
%Valor inicial para a linha neutra adimensional qsi=x/h
qi=0;
%Chamar sub-rotina para calcular o valor da fun��o fi=f(qi)
fi=Funcao(es,eu,beta,e0,ami,qi,akapa,alamb,ni,fyd,ani,nl);
%
%Valor final para a linha neutra adimensional qsi=x/h
qf=1000;
%Chamar sub-rotina para calcular o valor da fun��o ff=f(qf)
ff=Funcao(es,eu,beta,e0,ami,qf,akapa,alamb,ni,fyd,ani,nl);
%
prod=fi*ff;
%Modificando os extremos do intervalo solu��o at� que prod<=0
while (prod>0)
    qi=qf;
    fi=ff;
    qf=10*qf;
    ff=Funcao(es,eu,beta,e0,ami,qf,akapa,alamb,ni,fyd,ani,nl);
    prod=fi*ff;
end
%
%O intervalo solu��o foi definido
%A linha neutra qsi fica entre [qi,qf]
%
%Processo iterativo da bissecante
fk=1;
while (abs(fk)>0.001)
    qk=(qi*ff-qf*fi)/(ff-fi);
    fk=Funcao(es,eu,beta,e0,ami,qk,akapa,alamb,ni,fyd,ani,nl);
    prod=fk*fi;
    if(prod>=0)
        qi=qk;
        fi=fk;
    else
        qf=qk;
        ff=fk;
    end
end
%Converg�ncia alcan�ada
%qk � a raiz da fun��o f(qsi) dada na equa��o (2.5.11) do Volume 3 de Curso de Concreto Armado
%
%C�lculo da taxa mec�nica de armadura
if(abs(soma1)>=abs(soma2))
    %   Uso da equa��o (2.5.9) do Volume 3
    w=n*fyd*(ani-rc)/soma1;
else
    %   Uso da equa��o (2.5.10) do Volume 3
    w=n*fyd*(0.5*ani-ami-rc*bc)/soma2;
end
if(w<0)
    w=0;
end
%
%C�lculo da �rea de armadura total
w
ac
tcd
fyd
aas=w*ac*tcd/fyd
%
%Armadura m�nima da NBR-6118 para pilares
%
ani0=aand/(ac*fcd);
romin=0.15*fcd*ani0/fyd;
if(romin<0.004)
    romin=0.004;
end
asmin=romin*ac
%
%Armadura a ser adotada
ase=aas;
if(ase<asmin)
    ase=asmin;
end
%
%MOSTRAR O RESULTADO
%
% 'Armadura calculada: As(cm2)=',f6.2)
% 'Armadura minima: As,min(cm2)=',f6.2)
% 'Armadura a ser adotada: Ase(cm2)=',f6.2)=
end
% asfinal=max(asaux(1),asaux(2))