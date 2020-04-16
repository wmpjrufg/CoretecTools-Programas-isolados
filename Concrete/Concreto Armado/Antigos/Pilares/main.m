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

%Dimensionamento à Flexo-Compressão Normal
%Seções retangulares com várias camadas de armadura
%O programa está limitado a 20 camadas
%
%PROGRAMA PRINCIPAL
%Resistência característica à compressão do concreto em MPa
fck=20;
%
%Tensão de escoamento característica do aço em MPa
fyk=500;
%
%Módulo de elasticidade do aço em GPa
es=210;
%
%Coeficientes parciais de segurança:
%para o concreto
gamac=1.4;
%para o aço
gamas=1.15;
%para os esforços solicitantes
gamaf=1.4;
%Resistências de cálculo
fcd=fck/gamac;
fyd=fyk/gamas;
%% Dados da seção transvesral:
%Largura em cm
b=40;
%Altura em cm
h=20;
%Distância d%
dl=max(0.2*h);
%
%% Número de camadas de armadura:
%A seção deve ter, no mínimo, duas camadas de armadura
%nl>=2
nl=2;
%
%Número de barras das camadas de armadura:
%As camadas são numeradas de baixo para cima
%Cada camada deve ter, no mínimo, duas barras
% ni=[2 2];
ncamadas=2;
for z=1:nl
    ni(z)=ncamadas;
end
%Esforços solicitantes de serviço:
%Entrar com valores positivos
%Esforço normal (em kN)
ank=500;
% %Momento fletor (em kNm)
% amk=143.5/1.4;
%FIM DA ENTRADA DE DADOS
p_entrada(fcd,h,b,ank,le,M1dAx,M1dBx,M1dAy,M1dBy);
amk=max(Mdx/140,Mdy/140)
%% INÍCIO DOS CÁLCULOS
%
%Parâmetros do diagrama retangular
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
%Parâmetro kapa que define o ponto com deformação igual a eo no domínio 5
akapa=1-e0/eu;
%
%Conversão de unidades: transformando para kN e cm
fck=fck/10;
fyk=fyk/10;
fcd=fcd/10;
fyd=fyd/10;
es=100*es;
amk=100*amk;
%
%Resistências de cálculo
tcd=alfac*fcd;
%
%Esforços solicitantes de cálculo
aand=gamaf*ank;
amd=gamaf*amk;
%
%Cálculo do número total de barras na seção
n=0;
for i=1:nl
    n=n+ni(i);
end
%
%Parâmetro geométrico
delta=dl/h;
%
%Área da seção de concreto
ac=b*h;
%
%Esforcos reduzidos
ani=aand/(ac*tcd);
ami=amd/(ac*h*tcd);
%
%Caso particular de compressão centrada
%
if(ami==0)
    esi=e0/1000;
    w=(ani-1)*fyd/Tensao(es,esi,fyd);
    if(w<0)
        w=0;
    end
    %Cálculo da área de armadura total
    disp('armadura total')
    aas=w*ac*tcd/fyd
    return
end
%
%Flexo-compressão normal
%
%Montagem do vetor beta
%Ver equação (2.2.5) do Volume 3 de Curso de Concreto Armado
%
for i=1:nl
    beta(i)=delta+(nl-i)*(1-2*delta)/(nl-1);
end
%Processo iterativo da bissecante
%
%Determinação do intervalo solução
%
%Valor inicial para a linha neutra adimensional qsi=x/h
qi=0;
%Chamar sub-rotina para calcular o valor da função fi=f(qi)
fi=Funcao(es,eu,beta,e0,ami,qi,akapa,alamb,ni,fyd,ani,nl);
%
%Valor final para a linha neutra adimensional qsi=x/h
qf=1000;
%Chamar sub-rotina para calcular o valor da função ff=f(qf)
ff=Funcao(es,eu,beta,e0,ami,qf,akapa,alamb,ni,fyd,ani,nl);
%
prod=fi*ff;
%Modificando os extremos do intervalo solução até que prod<=0
while (prod>0)
    qi=qf;
    fi=ff;
    qf=10*qf;
    ff=Funcao(es,eu,beta,e0,ami,qf,akapa,alamb,ni,fyd,ani,nl);
    prod=fi*ff;
end
%
%O intervalo solução foi definido
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
%Convergência alcançada
%qk é a raiz da função f(qsi) dada na equação (2.5.11) do Volume 3 de Curso de Concreto Armado
%
%Cálculo da taxa mecânica de armadura
if(abs(soma1)>=abs(soma2))
    %   Uso da equação (2.5.9) do Volume 3
    w=n*fyd*(ani-rc)/soma1;
else
    %   Uso da equação (2.5.10) do Volume 3
    w=n*fyd*(0.5*ani-ami-rc*bc)/soma2;
end
if(w<0)
    w=0;
end
%
%Cálculo da área de armadura total
w
ac
tcd
fyd
aas=w*ac*tcd/fyd
%
%Armadura mínima da NBR-6118 para pilares
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