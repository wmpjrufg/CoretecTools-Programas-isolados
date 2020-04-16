% function [Mdx,Mdy,Nd,fyd,ex,ey] = p_entrada(fcd,h,b,Nk,le,M1dAx,M1dBx,M1dAy,M1dBy)
function [Nd,fyd,ex,ey] = p_entrada(fcd,h,b,Nk,le,M1dAx,M1dBx,M1dAy,M1dBy)
global Mdx Mdy
%Cálculo do coeficiente adicional Yn
if(abs(b)<=abs(h))
    menor_dim=abs(b);
else
    menor_dim=abs(h);
end
Yn=1.95-0.05*menor_dim;
if(Yn>1.25)
    Yn=1.25;
elseif(Yn<1)
    Yn=1;
end
disp("Yn")
disp(Yn)
%CÁLCULO DE Nd, e M1dmin
Nd=Yn*1.4*abs(Nk);
M1dminx=Nd*(1.5+0.03*b)
M1dminy=Nd*(1.5+0.03*h)
% %CÁLCULO DE Ocd
% if(abs(fck)<=50)
%     ac=0.85;
%     L=0.8;
% else
%     ac=0.85*(1-(abs(fck)-50)/200);
%     L=0.8-(abs(fck)-50)/400;
% end
% fcd=abs(fck)/1.4;
% Ocd=ac*fcd/10;
%CÁLCULO DE ab
%Eixo X
if(abs(M1dAx)>=abs(M1dBx))
    MAx=M1dAx;
    MBx=M1dBx;
else
    MAx=M1dBx;
    MBx=M1dAx;
end
if(MAx==0)
    abx=1;
else
    if(abs(M1dminx)>=abs(MAx))
        abx=1;
    else
        abx=0.6+0.4*MBx/MAx;
    end
end
if(abx>=1)
    abx=1;
elseif(abx<=0.4)
    abx=0.4;
end
disp("abx")
disp(abx)
%Eixo Y
if(abs(M1dAy)>=abs(M1dBy))
    MAy=M1dAy;
    MBy=M1dBy;
else
    MAy=M1dBy;
    MBy=M1dAy;
end
if(MAy==0)
    aby=1;
else
    if(abs(M1dminy)>=abs(MAy))
        aby=1;
    else
        aby=0.6+0.4*MBy/MAy;
    end
end
if(aby>=1)
    aby=1;
elseif(aby<=0.4)
    aby=0.4;
end
disp("aby")
disp(aby)
%NECESSIDADE DE ANÁLISE DE 2ª ORDEM
%Eixo X
if(abs(M1dAx)>=abs(M1dBx))
    e1x=abs(M1dAx)/Nd;
else
    e1x=abs(M1dBx)/Nd;
end
esbx=3.46*le/b;
disp("esbx")
disp(esbx)
L1x=(25+12.5*e1x/b)/abx;
if(L1x>=90)
    L1x=90;
elseif(L1x<=35)
    L1x=35;
end
if(esbx>=L1x)
    Lx=1;
else
    Lx=0;
end
disp("L1x")
disp(L1x)
%Eixo Y
if(abs(M1dAy)>=abs(M1dBy))
    e1y=abs(M1dAy)/Nd;
else
    e1y=abs(M1dBy)/Nd;
end
esby=3.46*le/h;
disp("esby")
disp(esby)
L1y=(25+12.5*e1y/h)/aby;
if(L1y>=90)
    L1y=90;
elseif(L1y<=35)
    L1y=35;
end
if(esby>=L1y)
    Ly=1;
else
    Ly=0;
end
disp("L1y")
disp(L1y)
%MOMENTO DE CÁLCULO NA DIREÇÃO X
%Momento de 1ª ordem
if(abs(M1dAx)>=abs(M1dBx))
    M1dx=abs(M1dAx);
else
    M1dx=abs(M1dBx);
end
if(M1dx>=abs(M1dminx))
    M1dx=M1dx;
else
    M1dx=abs(M1dminx);
end
%Momento de 2ª ordem
v=Nd/(abs(h)*abs(b)*fcd/10);
disp("v")
disp(v)
%metodo=1 (método da curvatura)
%metodo=2 (método da rigidez k)
metodo=1;
if(Lx==1)
    if(metodo==1)
        %Método da curvatura
        if(v<=0.5)
            curvx=0.005/abs(b);
        else
            curvx=0.005/abs(b)/(v+0.5);
        end
        disp("curvx")
        disp(curvx)
        
        M2dx=Nd*le^2/10*curvx;
        Mdx=abx*M1dx+M2dx;
        if(Mdx<=M1dx)
            Mdx=M1dx;
        else
            Mdx=Mdx;
        end
        ex=Mdx/Nd;
        Mdy=0;
    elseif(metodo==2)
        %Método da rigidez aproximada k
        A=5*b;
        B=b^2*Nd-Nd*le^2/320-5*b*abx*M1dx;
        C=-Nd*b^2*abx*M1dx;
        Mdx = abs(max((-B+sqrt(B^2-4*A*C))/2/A,(-B-sqrt(B^2-4*A*C))/2/A));
        ex=Mdx/Nd;
        Mdy=0;
    end
else
    ex=M1dx/Nd;
end
%MOMENTO DE CÁLCULO NA DIREÇÃO Y
%Momento de 1ª ordem
if(abs(M1dAy)>=abs(M1dBy))
    M1dy=abs(M1dAy);
else
    M1dy=abs(M1dBy);
end
if(M1dy>=abs(M1dminy))
    M1dy=M1dy;
else
    M1dy=abs(M1dminy);
end
%Momento de 2ª ordem
if(Ly==1)
    if(metodo==1)
        %Método da curvatura
        if(v<=0.5)
            curvy=0.005/abs(h);
        else
            curvy=0.005/abs(h)/(v+0.5);
        end
         disp("curvy")
        disp(curvy)
        M2dy=Nd*le^2/10*curvy;
        Mdy=aby*M1dy+M2dy;
        if(Mdy<=M1dy)
            Mdy=M1dy;
        else
            Mdy=Mdy;
        end
        ey=Mdy/Nd;
        Mdx=0;
    elseif(metodo==2)
        %Método da rigidez aproximada k
        A=5*h;
        B=h^2*Nd-Nd*le^2/320-5*h*aby*M1dy;
        C=-Nd*h^2*aby*M1dy;
        Mdy = abs(max((-B+sqrt(B^2-4*A*C))/2/A,(-B-sqrt(B^2-4*A*C))/2/A));
        ey=Mdy/Nd;
        Mdx=0;
    end
else
    ey=M1dy/Nd;
end